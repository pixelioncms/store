<?php

class TimevisitController extends CStatsController {

    public function actionIndex() {
        $this->pageName = Yii::t('StatsModule.default', 'TIMEVISIT');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );

        $vse = 0;
        if ($this->sort == "hi") {
            $sql = "SELECT substr(tm,-5,2) as tm FROM {{surf}} WHERE";
            $sql .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate'";
            $res = $this->db->createCommand($sql);
        } else {
            $sql = "SELECT substr(tm,-5,2) as tm,ip FROM {{surf}} WHERE";
            $sql .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' GROUP BY 2,1";
            $res = $this->db->createCommand($sql);
        }
        $results = $res->queryAll();
        if ($results) {
            $tmas = [];
            foreach ($results as $row) {
                $tmas[$row['tm']] += 1;
            }

            $result = array();
            ksort($tmas);
            $mmx = max($tmas);
            $cnt = array_sum($tmas);
            $times = array();
            $visits = array();
            foreach ($tmas as $tm => $val) {

                $vse += $val;

                if ($tm <> "23")
                    $tm2 = $tm + 1;
                else
                    $tm2 = "00";
                if (strlen($tm2) == 1)
                    $tm2 = "0" . $tm2;
                $par = $tm . ':00 - ' . $tm2 . ':00';
                $times[] = $par;
                $visits[] = array(
                    'y' => (int) $val,
                    'url' => "/admin/stats/timevisit/detail?pz=1&s_date=" . $this->sdate . "&f_date=" . $this->fdate . "&qs=" . $tm . ":&sort=" . (empty($this->sort) ? "ho" : $this->sort)
                );


                $result[] = array(
                    'time' => Html::link('' . $tm . ':00 - ' . $tm2 . ':00', '/admin/stats/timevisit/detail?pz=1&s_date=' . $this->sdate . '&f_date=' . $this->fdate . '&qs=' . $tm . ':&sort=' . (empty($this->sort) ? "ho" : $this->sort), array('target' => '_blank')),
                    'val' => $val,
                    'progressbar' => $this->progressBar(ceil(($val * 100) / $mmx), number_format((($val * 100) / $cnt), 1, '.', ''), (($this->sort == "hi") ? "success" : "warning")),
                );
            }
        }
        $dataProvider = new CArrayDataProvider($result, array(
            'sort' => array(
                // 'defaultOrder'=>'id ASC',
                'attributes' => array(
                    'time',
                    'val',
                ),
            ),
            'pagination' => array(
                'pageSize' => 24,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'times' => $times,
            'visits' => $visits,
        ));
    }

    public function actionDetail() {

        $qs = Yii::app()->request->getParam('qs');
        $tz = Yii::app()->request->getParam('tz');
        $sql = "SELECT day,dt,tm,refer,ip,proxy,host,lang,user,req FROM {{surf}} WHERE (tm LIKE '%" . addslashes($qs) . "%') AND dt >= '$this->sdate' AND dt <= '$this->fdate' " . (($_GET['pz'] == 1) ? "AND" . $this->_zp : "") . " " . (($this->sort == "ho") ? "GROUP BY " . (($tz == 7) ? "host" : "ip") : "") . " ORDER BY i DESC";
        $res = $this->db->createCommand($sql);


        $this->pageName = Yii::t('StatsModule.default', 'TIMEVISIT');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName => array('/admin/stats/timevisit'),
            $qs
        );


        foreach ($res->queryAll() as $row) {

            $ip = CMS::ip($row['ip']);
            if ($row['proxy'] != "") {
                $ip .= '<br>';
                $ip .= Html::link('через proxy', '?item=ip&qs=' . $row['proxy'], array('target' => '_blank'));
            }
            $this->result[] = array(
                'date' => StatsHelper::$DAY[$row['day']] . ' ' . $row['dt'],
                'time' => $row['tm'],
                'refer' => StatsHelper::renderReferer($row['refer']),
                'ip' => $ip,
                'host' => StatsHelper::getRowHost($row['ip'], $row['proxy'], $row['host'], $row['lang']),
                'user_agent' => StatsHelper::getRowUserAgent($row['user'], $row['refer']),
                'page' => Html::link($row['req'], $row['req'], array('target' => '_blank')),
            );
        }
        $dataProvider = new CArrayDataProvider($this->result, array(
            'sort' => array(
                'attributes' => array(
                    'date',
                    'time',
                    'ip'
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        $this->render('detail', array('dataProvider' => $dataProvider));
    }

}
