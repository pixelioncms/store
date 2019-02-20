<?php

class CurrencynbuBlock extends BlockWidget {

    public function run() {
        if (!Yii::app()->getDb()->schema->getTable('{{currencies_nbu_base}}')) {
            Yii::app()->tpl->alert('info', 'table no found', false);
            return;
        }
        $startdate = date('Y-m-d');
        $date = new DateTime($startdate);
        $date->modify("-1 week");
        $date->modify("+1 days");
        $end = new DateTime($startdate);
        $end->modify("+1 days");
        //echo $date->format('Y-m-d');
        $currencies = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{currencies_nbu_base}}')
                ->andWhere('date >= :start', array(':start' => $date->format('Y-m-d')))
                ->andWhere('date <= :end', array(':end' => $end->format('Y-m-d')))
                ->order('date ASC')
                ->queryAll();


        //  echo $startdate;

        $data = array();
        $series = array();
        $categories = array();
        foreach ($currencies as $currency) {

            $data[$currency['cur']]['rate'][] = round($currency['rate'], 2);
            $data[$currency['cur']]['data'][] = CMS::date($currency['date'], false, true);
        }
        foreach ($data as $cur => $curdata) {
            $series[] = array(
                'name' => $cur,
                'data' => $curdata['rate'],
            );
            $categories[] = $curdata['data'];
        }
        // print_r($categories);
        $this->render($this->skin, array(
            'data' => $data,
            'series' => $series,
            'categories' => $categories
        ));
    }

}
