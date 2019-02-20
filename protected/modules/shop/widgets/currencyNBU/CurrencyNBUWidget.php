<?php

/**
 * Currency NBU widget switch
 * 
 * 
 * $this->widget('mod.shop.widgets.currencyNBU.CurrencyNBUWidget',array(
 *    'date'=>20160516,
 *    'valcode'=>'USD'
 * ));
 * 
 * 
 * @version 1.0
 * @package widgets.modules.shop
 * @uses CWidget
 */
class CurrencyNBUWidget extends CWidget {

    protected $options = array();
    public $date; //Ymd
    public $valcode; //USD,EUR
    private $url = 'http://bank.gov.ua/NBUStatService/v1/statdirectory/exchange';

    public function init() {
        parent::init();
    }

    public function run() {
        if (isset($this->valcode)){
            $this->options['valcode'] = $this->valcode;
        }
        if (isset($this->date)) {
            $this->options['date'] = $this->date;
        } else {
            $this->options['date'] = date('Ymd');
        }

        $this->options['json'] = true;

        ksort($this->options);
        $query = $this->url . '?' . $this->params($this->options);
        $res = file_get_contents($query);
        //  return json_decode($res, true);


        $this->render($this->skin, array('result' => json_decode($res, true)));
    }

    private function params($params) {
        $pice = array();
        foreach ($params as $k => $v) {
            $pice[] = $k . '=' . urlencode($v);
        }
        return implode('&', $pice);
    }

}
