<?php

/**
 * StatisticsCommand command
 * 
 * 
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @uses CConsoleCommand
 * @package commands
 * 
 */
class StatisticsCommand extends CConsoleCommand {

    private $_tableName = '{{currencies_nbu_base}}';
    private $_existTable = false;
    private $_currencies_lias = array('USD', 'EUR', 'RUB');

    public function run($args) {



        //$this->existsTable();
        //$this->insertPeriod();
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
            $cc = Yii::app()->getDb()->createCommand(array(
                        'from' => $this->_tableName,
                    ))->queryAll();
            // if (count($cc) > 0) {
            $last = Yii::app()->getDb()->createCommand(array(
                        //'select' => array('max(date) AS date'),
                        'select' => array('select' => 'max(date) AS date'),
                        'from' => $this->_tableName,
                    ))->queryRow();


            if ($last['date']) {
                $begin = new DateTime($last['date']);
                //  $begin = $begin->modify('+1 day');

                $end = new DateTime('now'); //date('Y-m-d')
                //  $end = $end->modify('+1 day');
            } else {
                $begin = new DateTime('now'); //date('Y-m-d')
                $begin = $begin->modify('-30 day');
                $end = new DateTime('now'); //date('Y-m-d')
                $end = $end->modify('+2 day');
            }




            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);


            foreach ($this->_currencies_lias as $currency) {
                foreach ($daterange as $date) {
                    Yii::log($date->format("Ymd"), 'info', 'console');
                    $connect = $this->curlConnect($currency, $date->format("Ymd"));
                    if ($connect) {
                        Yii::log(date('Y-m-d', strtotime($connect[0]['exchangedate'])), 'info', 'console');

                        Yii::app()->getDb()->createCommand()->insert($this->_tableName, array(
                            'type' => 'NBU',
                            'cur' => $currency,
                            'rate' => $connect[0]['rate'],
                            'date' => date('Y-m-d', strtotime($connect[0]['exchangedate'])),
                        ));
                    }
                }
            }
            /* } else {

              $begin = new DateTime('now');
              //$begin = $begin->modify('+1 day');


              $end = new DateTime('now');
              $end = $end->modify('+1 day');

              $interval = new DateInterval('P1D');
              $daterange = new DatePeriod($begin, $interval, $end);
              print_r($begin);
              print_r($end);
              echo count($daterange);
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
              } */
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
