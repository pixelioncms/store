<?php

/**
 * Console command
 * 
 * Example console command open server
 * <code>
 *   Panix@PC e:\OpenServer\domains\corner-cms.loc\protected
 *   $ console currencynbubase
 * </code>
 * 
 * Example hosting ukraine
 * <code>
 * 0 0 * * * /usr/local/php54/bin/php -f /home/corner2/corner-cms.com/www/protected/console.php currencynbubase 
 * </code>
 * 
 * Example for Open server:
 * </code>
 * %progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\userdata\temp\config\php.ini -q -f %sitedir%\SITE.loc\protected\console.php currencyNBUBase
 * </code>
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @uses CConsoleCommand
 * @package commands
 * 
 */
class CurrencyNBUBaseCommand extends CConsoleCommand {

    const TIMEZONE = 'Europe/Kiev';

    private $_tableName = '{{currencies_nbu_base}}';
    private $_existTable = false;
    private $_currencies_lias = array('USD', 'EUR', 'RUB');

    public function run($args) {
        $this->existsTable();
        $this->insertPeriod();
        $this->updateCurrenciesShop();
    }

    private function existsTable() {
        if (!Yii::app()->getDb()->schema->getTable($this->_tableName)) {
            Yii::app()->getDb()->createCommand()->createTable($this->_tableName, array(
                'id' => 'pk',
                'type' => 'string NOT NULL',
                'cur' => 'string NOT NULL',
                'rate' => 'string NOT NULL',
                'date' => 'date',
            ));
            Yii::log('create table', 'info', 'console');
            $this->_existTable = false;
        } else {
            $this->_existTable = true;
        }
    }

    private function insertPeriod() {

        if ($this->_existTable) {


            $last = Yii::app()->getDb()->createCommand(array(
                        //'select' => array('max(date) AS date'),
                        'select' => array('select' => 'max(date) AS date'),
                        'from' => $this->_tableName,
                    ))->queryRow();


            if ($last['date']) {
                $begin = new DateTime($last['date']);
                $begin->setTimezone(new DateTimeZone(self::TIMEZONE));

                // if(strtotime($last['date']) == strtotime(date('Y-m-d'))){
                $begin = $begin->modify('+1 day');
                //}
                $end = new DateTime('now'); //date('Y-m-d')
                $end->setTimezone(new DateTimeZone(self::TIMEZONE));
                //$end = $end->modify('+1 day');
            } else {
                $begin = new DateTime('now'); //date('Y-m-d')
                $begin->setTimezone(new DateTimeZone(self::TIMEZONE));
                $begin = $begin->modify('-30 day');

                $end = new DateTime('now'); //date('Y-m-d')
                $end->setTimezone(new DateTimeZone(self::TIMEZONE));
                $end = $end->modify('+2 day');
            }




            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);


            foreach ($this->_currencies_lias as $currency) {
                foreach ($daterange as $date) {
                    Yii::log($date->format("Y-m-d"), 'info', 'console');
                    $connect = $this->curlConnect($currency, $date->format("Ymd"));
                    if ($connect) {
                        //  Yii::log(date('Y-m-d', strtotime($begin)), 'info', 'console');
                        //     Yii::log(date('Y-m-d', strtotime($end)), 'info', 'console');
                        Yii::app()->getDb()->createCommand()->insert($this->_tableName, array(
                            'type' => 'NBU',
                            'cur' => $currency,
                            'rate' => $connect[0]['rate'],
                            'date' => date('Y-m-d', strtotime($connect[0]['exchangedate'])),
                        ));
                    }
                }
            }
        } else {
            Yii::log('no found table', 'info', 'console');
        }
    }

    private function updateCurrenciesShop() {
        if (Yii::app()->hasModule('shop') && false) {
            echo 'update shop';
        } else {
            echo 'no install module shop';
        }
    }

    private function curlConnect($cur, $date) {
        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'timeout' => 320,
                'setOptions' => array(
                    CURLOPT_HEADER => false,
                    CURLOPT_USERAGENT => 'cmsbot',
                //    CURLOPT_FOLLOWLOCATION => true,
                ),
            );
            $serverUrl = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=' . $cur . '&date=' . $date . '&json';
            $connent = $curl->run($serverUrl);
            if (!$connent->hasErrors()) {
                return CJSON::decode($connent->getData());
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
