<?php

class StatisticsController extends AdminController
{

    /**
     * Display stats by count
     */
    public function actionIndex()
    {

        $this->breadcrumbs = array($this->pageName);
        $this->icon = $this->module->adminMenu['orders']['items'][1]['icon'];
        $data = array();
        $data_total = array();
        $request = Yii::app()->request;

        $year = (int)$request->getParam('year', date('Y'));
        $month = (int)$request->getParam('month', date('n'));
        $orders = Order::model()->between("{$year}-01-01 00:00:00", "{$year}-12-01 23:59:59")->findAll();

        $this->pageName = Yii::t('CartModule.admin', 'STATS').' '.$year;

        // $orders = $this->loadOrders($year, $month);
        // $grouped = $this->groupOrdersByDay($orders);

        /*for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); ++$i) {
            $count = 0;
            $totalPrice = 0;
            if (array_key_exists($i, $grouped)) {
                $count = sizeof($grouped[$i]);
                $totalPrice = $this->getTotalPrice($grouped[$i]);
            }

            //$data[] = array('day' => $i, 'value' => $count);
            $data[] = $count;
            $data_total[] = $totalPrice;
            echo $i;
            echo '<br>';
        }*/
        $data = array();
        $currentMonths = array();
        $percent = array();
        $highchartsDrill = array();
        foreach ($orders as $order) {
            $index = date('n', strtotime($order->date_create));

            $currentMonths[$index][] = $order;
            $percent[$index] = 0;
            $data[$index] = array();
            $data[$index]['total_price'] = 0;
            $data[$index]['product_count'] = 0;
            $data[$index]['order_count'] = count($currentMonths[$index]);
            foreach ($currentMonths[$index] as $o) {
                $percent[$index] += count($o->products);
                $data[$index]['total_price'] += $o->total_price;
                $data[$index]['product_count'] = $percent[$index];
            }

        }


        $counter = array_sum($percent);
        for ($i = 0; $i < 12; $i++) {
            $index = $i + 1;
            $monthDaysCount = cal_days_in_month(CAL_GREGORIAN, $index, 2018);
            $highchartsDrill[$i] = array();
            $highchartsDrill[$i]['id'] = "Month_{$index}";
            $highchartsDrill[$i]['name'] = date('F', strtotime("{$year}-{$index}"));


            foreach (range(1, $monthDaysCount) as $day) {
                $highchartsDrill[$i]['data'][] = [date('l', strtotime("{$year}-{$index}-{$day}")).' '.$day.'', 4.4 + $index,'444'];
               /* $highchartsDrill[$i]['data'][] = array(
                    //'x' => $i,
                    'y' => '213',
                    'name' => $index,
                    'value' => 12321132,
                    'color' => $this->getSeasonColor($index),
                );*/
            }


            $total_price = (isset($data[$index]['total_price'])) ? $data[$index]['total_price'] : 0;
            $product_count = (isset($data[$index]['product_count'])) ? $data[$index]['product_count'] : 0;
            $order_count = (isset($data[$index]['order_count'])) ? $data[$index]['order_count'] : 0;
            $val = (isset($percent[$index])) ? ($percent[$index] * 100) / $counter : 0;

            $highchartsData[] = array(
                //'x' => $i,
                'y' => $val,
                'name' => date('F', strtotime("{$year}-{$index}")),
                'products' => $product_count,
                'value' => $order_count,
                'total_price' => Yii::app()->currency->number_format($total_price) . ' ' . Yii::app()->currency->active->symbol,
                'color' => $this->getSeasonColor($index),
                "drilldown" => "Month_{$index}"
            );
        }

        // echo CVarDumper::dump($highchartsDrill,10,true);die;


        $this->render('index', array(
            'highchartsData' => $highchartsData,
            'highchartsDrill' => $highchartsDrill,
            'data_total' => $data_total,
            'year' => $year,
            'month' => $month
        ));
    }

    public function getSeasonColor($mouth)
    {

        if (in_array($mouth, array(12, 1, 2))) {//winter
            return '#44cdff';
        } elseif (in_array($mouth, array(9, 10, 11, 12))) {//osen'

            return '#b79c82';
        } elseif (in_array($mouth, array(3, 4, 5))) {//vesna
            return '#ff9e44';
        } elseif (in_array($mouth, array(6, 7, 8))) {//leto
            return '#43b749';
        }
    }

    /**
     * @param $year
     * @param $month
     * @return array
     */
    public function loadOrders($year, $month)
    {
        $month = (int)$month;

        if ($month < 10)
            $month = '0' . $month;

        $date_match = (int)$year . '-' . $month;

        $query = new CDbCriteria(array(
            'condition' => "date_create LIKE '$date_match%'"
        ));

        return Order::model()->findAll($query);
    }

    public function groupOrdersByDay(array $orders)
    {
        $result = array();

        foreach ($orders as $order) {
            $day = date('j', strtotime($order->date_create));
            if (!isset($result[$day]))
                $result[$day] = array();

            $result[$day][] = $order;
        }

        return $result;
    }

    /**
     * @param array $orders
     * @return int
     */
    public function getTotalPrice(array $orders)
    {
        $result = 0;

        foreach ($orders as $o)
            $result += $o->getFull_price();

        return $result;
    }

    /**
     * @return array
     */
    public function getAvailableYears()
    {
        $result = array();
        $command = Yii::app()->db->createCommand('SELECT date_create FROM {{order}} ORDER BY date_create')->queryAll();

        foreach ($command as $row) {
            $year = date('Y', strtotime($row['date_create']));
            $result[$year] = $year;
        }

        return $result;
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                'url' => array('/admin/cart'),
                'icon' => Html::icon('icon-cart'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Index')),
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATUSES'),
                'url' => array('/admin/cart/statuses'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Statuses.*', 'Cart.Statuses.Index')),
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'HISTORY'),
                'url' => array('/admin/cart/history'),
                'icon' => Html::icon('icon-history'),
                'visible' => Yii::app()->user->openAccess(array('Cart.History.*', 'Cart.History.Index')),
            ),
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/cart/settings'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Settings.*', 'Cart.Settings.Index')),
            ),
        );
    }

}
