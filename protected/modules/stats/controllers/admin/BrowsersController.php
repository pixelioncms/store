<?php

class BrowsersController extends CStatsController {

    public function actionIndex() {
        $this->pageName = Yii::t('StatsModule.default', 'BROWSERS');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );


        $vse = 0;
        $k = 0;
        $bmas = array();
        $ipmas = [];

        if ($this->sort == "hi") {
            $sql = "SELECT user FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND " . $this->_zp;
            $command = $this->db->createCommand($sql);
            foreach ($command->queryAll() as $row) {
                $bmas[StatsHelper::getBrowser($row['user'])] ++;
            }
        } else {
            $bcount = 1;
            $sql = "SELECT user, ip FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND " . $this->_zp . " GROUP BY ip, user";
            $command = $this->db->createCommand($sql);
            foreach ($command->queryAll() as $row) {
                $gb = StatsHelper::getBrowser($row['user']);
               // $bmas[$gb] = []; //NEW
                if (!isset($ipmas[$row['ip']][$gb])) {
                    $bmas[$gb] = $bcount;
                    $ipmas[$row['ip']][$gb] = 1;
                }
                $bcount++;
            }
        }



//print_r($bmas);die;
        arsort($bmas);
        $mmx = max($bmas);

        $cnt = array_sum($bmas);
        $pie = array();
        $helper = new StatsHelper;
        foreach ($bmas as $brw => $val) {
echo ($val * 100) / $cnt;
echo '<br>';
            $k++;
            $vse += $val;
            $this->result[] = array(
                'num' => $k,
                'browser' => $helper->browserName($brw),
                'val' => $val,
                'progressbar' => $this->progressBar(ceil(($val * 100) / $mmx), number_format((($val * 100) / $cnt), 1, ',', ''), (($this->sort == "hi") ? "success" : "warning")),
                'detail' => StatsHelper::linkDetail('/admin/stats/browsers/detail?s_date=' . $this->sdate . '&f_date=' . $this->fdate . '&brw=' . (empty($brw) ? "другие" : $brw) . "&sort=" . (empty($this->sort) ? "ho" : $this->sort)),
            );
            $pie[] = array(
                'name' => $helper->browserName($brw),
                'y' => ceil(($val * 100) / $mmx),
                'hosts' => $val
                    //  'sliced'=> true,
                    //'selected'=> true
            );
        }





        $dataProvider = new CArrayDataProvider($this->result, array(
            'sort' => array(
                // 'defaultOrder'=>'id ASC',
                'attributes' => array(
                    'browser',
                    'val'
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'bmas' => $bmas,
            'cnt' => $cnt,
            'vse' => $vse,
            'pie' => $pie,
            'mmx' => $mmx,
            'brw' => $brw,
            'k' => $k,
        ));
    }

    public function actionView1331312132() {
        $this->pageName = 'dsadsa';
        $brw = $_GET['brw'];


        $vse = 0;
        $k = 0;
        //$db = Yii::app()->db;
        if ($this->sort == "hi") {
            $sql = "SELECT user, COUNT(user) cnt FROM {{surf}} WHERE";
            $sql .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' " . (isset($brw) ? StatsHelper::GetBrw($brw) : "") . " GROUP BY user ORDER BY 2 DESC";
            $res = $this->db->createCommand($sql);
            $full_sql = "SELECT SUM(t.cnt) as cnt FROM (" . $sql . ") t";
            $r = $this->db->createCommand($full_sql);
        } else {

            $sql = "CREATE TEMPORARY TABLE {{tmp_surf}} SELECT ip, user FROM {{surf}} WHERE";
            $sql .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' " . (isset($brw) ? StatsHelper::GetBrw($brw) : "") . " GROUP BY ip" . (!isset($brw) ? ",user" : "");
            $sql2 = "SELECT user, COUNT(user) cnt FROM {{tmp_surf}} GROUP BY user ORDER BY 2 DESC";
            $res = $this->db->createCommand($sql);
            $transaction = $this->db->beginTransaction();
            try {
                $this->db->createCommand($sql2)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }


            $z3 = "SELECT SUM(t.cnt) as cnt FROM (" . $sql2 . ") t";


            $transaction2 = $this->db->beginTransaction();
            try {
                $this->db->createCommand($sql)->execute();
                $transaction2->commit();
            } catch (Exception $e) {
                $transaction2->rollBack();
            }

            $r = $this->db->createCommand($z3);
        }
        $smd = $r->queryRow();
        $cnt = $smd['cnt'];
        if (!empty($brw)) {
            switch ($brw) {
                case "ie.png": $browserName = "MS Internet Explorer";
                    break;
                case "opera.png": $browserName = "Opera";
                    break;
                case "firefox.png": $browserName = "Firefox";
                    break;
                case "chrome.png": $browserName = "Google Chrome";
                    break;
                case "mozilla.png": $browserName = "Mozilla";
                    break;
                case "safari.png": $browserName = "Apple Safari";
                    break;
                case "mac.png": $browserName = "Macintosh";
                    break;
                case "maxthon.png": $browserName = "Maxthon (MyIE)";
                    break;
                default: $browserName = "другие";
                    break;
            }
        }

        $this->render('view', array(
            'items' => $res->queryAll(),
            'cnt' => $cnt,
            'max' => $max,
            'browserName' => $browserName,
            'vse' => $vse,
            'k' => $k,
                // 'pos' => $pos
        ));
    }

    public function actionDetail() {
        $sql = "SELECT day,dt,tm,refer,ip,proxy,host,lang,user,req FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' " . StatsHelper::GetBrw($_GET['brw']) . (($pz == 1) ? " AND" . $this->_zp : "") . " " . (($this->sort == "ho") ? "GROUP BY ip" : "") . " ORDER BY i DESC";
        $cmd = $this->db->createCommand($sql);

        $items = $cmd->queryAll();



        foreach ($items as $row) { //StatsHelper::$MONTH[substr($row['dt'], 4, 2)]
            $ip = CMS::ip($row['ip']);

            if ($row['proxy'] != "") {
                $ip .= '<br>';
                $ip .= Html::link('через proxy', '?item=ip&qs=' . $row['proxy'], array('target' => '_blank'));
            }

            $this->result[] = array(
                'date' => StatsHelper::$DAY[$row['day']] . ' ' . CMS::date($row['dt'] . ' ' . $row['tm']),
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
                // 'defaultOrder'=>'id ASC',
                'attributes' => array(
                    'date',
                ),
            ),
            'pagination' => array(
                'pageSize' => 101,
            ),
        ));

        $this->render('detail', array(
            'dataProvider' => $dataProvider,
        ));
    }

}
