<?php

/**
 * Example console command open server
 *   Panix@PC e:\OpenServer\domains\site.loc\protected
 *   $ console currencynbubase
 * 
 * hosting
 * 0 0 * * * /usr/local/php54/bin/php -f /home/corner2/corner-cms.com/www/protected/console.php currencynbubase 
 * 
 * Example for Open server:
 * %progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\userdata\temp\config\php.ini -q -f %sitedir%\SITE.loc\protected\console.php currencyNBUBase
 */
class CurrencyNBUBaseCommand extends CConsoleCommand {

    private $_tableName = '{{currencies_nbu_base}}';
    private $_existTable = false;
    private $_currencies_lias = array('USD', 'EUR', 'RUB');


    public function run($args) {
        $this->existsTable();
        $this->insertPeriod();
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
        } else {
            $this->_existTable = true;
        }
    }

    private function insertPeriod() {

        if ($this->_existTable) {
            $last = Yii::app()->getDb()->createCommand(array(
                        'select' => array('select' => 'max(date) AS date'),
                        'from' => $this->_tableName,
                    ))->queryRow();

            $curl = Yii::app()->curl;
            if ($last) {
                $begin = new DateTime($last['date']);
                $begin = $begin->modify('+1 day');
            } else {
                $begin = new DateTime(date('Y-m-d'));
                $begin = $begin->modify('-30 day');
            }

            $end = new DateTime(date('Y-m-d'));
            $end = $end->modify('+2 day');


            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);


            foreach ($this->_currencies_lias as $currency) {
                foreach ($daterange as $date) {
                    $connect = $this->curlConnect($currency, $date->format("Ymd"));
                    if ($connect) {
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
