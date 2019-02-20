<?php

//Yii::import('application.modules.core.models.BaseModel');

//Yii::import('application.components.*');

class CurrencyCommand extends CConsoleCommand {

    private $_src_text = '';         // National Bank page content
    private $_matches = array();     // Filtered Bank page content by regexp
    private $_regexp = false;
    private $_limit_try = 10; // Attempt connections

    public function run($args) {
        Yii::log('GetCurrencyCommand job started', 'info', 'console');
        $this->initVars();
        Yii::log('Getting file', 'info', 'console');
        if (!$this->downloadPage()) {
            Yii::log('Cannot get file, check internet connection', 'info', 'console');
            return;
        }else
            Yii::log('File received', 'info', 'console');;

        Yii::log('Parsing source', 'info', 'console');
        if (!$this->parcePage()) {
            Yii::log('Cannot parce source', 'info', 'console');
            return;
        } else
            Yii::log('Data is selected', 'info', 'console');
        Yii::log('Processing', 'info', 'console');
        if (!$this->updateCurrency()) {
            Yii::log('Currensy is not updated', 'info', 'console');
            return;
        } else
            Yii::log('Currency updated', 'info', 'console');
        Yii::log('Complete', 'info', 'console');
    }

    /**
     * Init vars
     */
    private function initVars() {
        $this->_regexp = '~                                                                                                                                                  
            <tr>\s*                                                                                                                                          
            <td[^>]*>([^<]*)</td>\s* #код цифровой                                                                                                           
            <td[^>]*>([^<]*)</td>\s* #код буквенный                                                                                                          
            <td[^>]*>([^<]*)</td>\s* #количество единиц                                                                                                      
            <td[^>]*>([^<]*)</td>\s* #название валюты                                                                                                        
            <td[^>]*>([^<]*)</td>\s* #официальный курс нацбанка                                                                                              
            </tr>                                                                                                                                            
        ~uimx';
    }

    /**
     * Download Page
     * @return boolean
     */
    private function downloadPage() {
        $geted = false;
        $tryNumber = 1;
        while ((!$geted) && ($tryNumber <= $this->_limit_try)) { //выполняем в цикле до тех пор, пока не получим, на случай недоступности сайта нацбанка
            Yii::log('try ' . $tryNumber . ': ', 'info', 'console');
            $src_text = file_get_contents('http://www.bank.gov.ua/control/uk/curmetal/detail/currency?period=daily');
            if ($src_text) {
                $this->_src_text = $src_text;
                $geted = true;
            } else {
                Yii::log('File not received, waiting', 'info', 'console');
                $tryNumber++;
                sleep(10);
            }
        }
        return $geted;
    }

    /**
     * Parsing page
     * @return boolean 
     */
    private function parcePage() {
        $result = false;

        if (preg_match_all($this->_regexp, $this->_src_text, $matches)) { // search by text
            $result = true;
            Yii::log('Data is selected', 'info', 'console');
            unset($matches[0]);  // Remove useless array from found resuts
            $this->_matches = $matches;
        }
        else
            Yii::log('Cannot select data', 'info', 'console');
        return $result;
    }

    /**
     * Select data from DB
     * @return boolean
     */
    private function updateCurrency() {
        Yii::import('application.modules.shop.models.*');
        Yii::log('updateCurrency()', 'info', 'console');
        $result = true;
        $currencyArray = ShopCurrency::model()->findAll();
        Yii::log('ShopCurrency', 'info', 'console');
        foreach ($currencyArray as $currency) {
            if ($currency->id != 1) { // skip UAH
                echo "processing " . $currency->iso . ": ";
                Yii::log('processing '.$currency->iso, 'info', 'console');
                $key = array_search($currency->iso, $this->_matches[2]);     // find currency ID
                if ($key) {
                    $currency->rate = (float) $this->_matches[5][$key] / (float) $this->_matches[3][$key];
                    $result = $result && ($currency->save(false,false));
                    if ($result)
                        Yii::log('Refreshed', 'info', 'console');
                }
                else
                    Yii::log('Currency not found', 'info', 'console');
            }
        }
        return $result;
    }

}

