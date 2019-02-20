<?php

class DetailController extends CStatsController {

    public function actionIndex($date) {
        
    }

    public function actionOther($date) {
        $stats = Yii::app()->stats->initRun();
       // $zp = $stats['zp'];
        $site = $stats['site'];
        $se_n = $stats['se_n'];
        $se_nn = $stats['se_nn'];
        $sql = "SELECT i,refer,ip,proxy,host,lang,user,tm,req FROM {{surf}} WHERE dt='" . $date . "' AND " . $this->_zp . " ORDER BY i ASC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();

        $f_se = array("yand", "google.", "go.mail.ru", "rambler.", "search.yahoo", "search.msn", "bing", "search.live.com");
        $f_se = array_merge($f_se, $se_nn);

        foreach ($cmd->queryAll(false) as $row) {

            //while ($row = mysql_fetch_row($rs)) {
            $refer = StatsHelper::Ref($row[1]);
            $skip = 0;
            foreach ($f_se as $val) {
                if (@stristr($refer, $val))
                    $skip = 1;
            }
            if (@stristr($refer, $site) and @ stripos($refer, $site) == 0)
                $skip = 1;

            if (@array_key_exists($row[2], $i1_ip)) {
                if ((is_array($refer) or ( ($refer != "") and ! (stristr($refer, "://" . $site) and stripos($refer, "://" . $site, 6) == 0) and ! (stristr($refer, "://www." . $site) and stripos($refer, "://www." . $site, 6) == 0)) or $skip == 1))
                    ;
                else
                    $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
            if (is_string($refer) and $refer != "" and ! (stristr($refer, "://" . $site) and stripos($refer, "://" . $site, 6) == 0) and ! (stristr($refer, "://www." . $site) and stripos($refer, "://www." . $site, 6) == 0) and $skip == 0) {
                $i1[$row[0]] = array($row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i1_ip[$row[2]] = $row[0];
                $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
        }
        $i1 = array_reverse($i1, true);
        foreach ($i1 as $id => $row) {


            $result[] = array(
                'refer' => StatsHelper::checkIdna($row[0]),
                'ip' => StatsHelper::getRowIp($row[1], $row[2]),
                'host' => StatsHelper::getRowHost($row[1], $row[2], $row[3], $row[4]),
                'user_agent' => StatsHelper::getRowUserAgent($row[5], $row[3]),
                'timelink' => StatsHelper::timeLink($i2, $id),
            );
        }


        $dataProvider = new CArrayDataProvider($result, array(
            'sort' => array(
                'attributes' => array(
                    'ip',
                    'refer',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('other', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionSearch($date) {
        $this->pageName = 'search';
        $stats = Yii::app()->stats->initRun();
       // $zp = $stats['zp'];
        $site = $stats['site'];
        $sql = "SELECT i,refer,ip,proxy,host,lang,user,tm,req FROM {{surf}} WHERE dt='" . $date . "' AND " . $this->_zp . " ORDER BY i ASC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();
        foreach ($cmd->queryAll(false) as $row) {
            $refer = StatsHelper::Ref($row[1]);
            if (@array_key_exists($row[2], $i1_ip)) {
                if ((is_array($refer) or ( ($row[1] != "") and ! stristr($row[1], "://" . $site) and ! stristr($row[1], "://www." . $site))))
                    ;
                else
                    $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
            if (is_array($refer)) {
                $i1[$row[0]] = array($row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i1_ip[$row[2]] = $row[0];
                $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
        }
        $i1 = array_reverse($i1, true);
        foreach ($i1 as $id => $row) {
            $refer = StatsHelper::Ref($row[0]);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                $refer1 = StatsHelper::checkSearchEngine($row[0], $engine, $query);
            } else {
                $refer1 = StatsHelper::checkIdna($row);
            }

            $result[] = array(
                'refer' => $refer1,
                'ip' => StatsHelper::getRowIp($row[1], $row[2]),
                'host' => StatsHelper::getRowHost($row[1], $row[2], $row[3], $row[4]),
                'user_agent' => StatsHelper::getRowUserAgent($row[6], $row[1]),
                'timelink' => StatsHelper::timeLink($i2, $id),
            );
        }

        $dataProvider = new CArrayDataProvider($result, array(
            'sort' => array(
                'attributes' => array(
                    'ip',
                    'refer',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('search', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionHits($date) {

        $this->pageName = Yii::t('StatsModule.default', 'HITS_FOR',array('{date}'=>$date));
        
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );
        
        $sql = "SELECT i,refer,ip,proxy,host,lang,user,tm,req FROM {{surf}} WHERE dt='" . $date . "' AND " . $this->_zp . " ORDER BY i ASC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();
        foreach ($cmd->queryAll() as $row) {
            //TODO: need fix, bag @array_key_exists "array_key_exists() expects parameter 2 to be array, null given"
            if (@array_key_exists($row['proxy'], $i1_ip)) {
                $i2[$i1_ip[$row['ip']]][] = array($row['tm'], $row['req']);
            } else {
                $i1[$row['i']] = array($row['refer'], $row['ip'], $row['proxy'], $row['host'], $row['lang'], $row['user']);
                $i1_ip[$row['ip']] = $row['i'];
                $i2[$i1_ip[$row['ip']]][] = array($row['tm'], $row['req']);
            }
        }
        $i1 = array_reverse($i1, true);

        foreach ($i1 as $id => $row2) {
              $refer = StatsHelper::Ref($row2[0]);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                $refer1 = StatsHelper::checkSearchEngine($row2[0], $engine, $query);
            } else {
                $refer1 = StatsHelper::checkIdna($row2[0]);
            }
            $result[] = array(
                'refer' => $refer1,
                'ip' => StatsHelper::getRowIp($row2[1], $row2[2]),
                'host' => StatsHelper::getRowHost($row2[1], $row2[2], $row2[3], $row2[4]),
                'user_agent' => StatsHelper::getRowUserAgent($row2[5], $row2[1]),
                'timelink' => StatsHelper::timeLink($i2, $id),
            );
        }
        $dataProvider = new CArrayDataProvider($result, array(
            'sort' => array(
                'attributes' => array(
                    'ip',
                    'refer',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('hits', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionHosts($date) {
       $this->pageName = Yii::t('StatsModule.default', 'HOSTS_FOR',array('{date}'=>$date));
        
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );

        $sql = "SELECT tm,refer,ip,proxy,host,lang,user,req from {{surf}} WHERE dt='" . $date . "' AND " . $this->_zp . " GROUP BY ip ORDER BY i DESC";
        $cmd = Yii::app()->db->createCommand($sql);
        foreach ($cmd->queryAll() as $row) {
       
            $refer = StatsHelper::Ref($row['refer']);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                $refer1 = StatsHelper::checkSearchEngine($row['refer'], $engine, $query);
            } else {
                $refer1 = StatsHelper::checkIdna($row['refer']);
            }
            $this->result[] = array(
                'time' => $row['tm'],
                'refer' => $refer1,
                'ip' => StatsHelper::getRowIp($row['ip'], $row['proxy']),
                'host' => StatsHelper::getRowHost($row['ip'], $row['proxy'], $row['host'], $row['lang']),
                'user_agent' => StatsHelper::getRowUserAgent($row['user'], $row['refer']),
                'timelink' => Html::link($row['req'], $row['req']),
            );
        }

        $dataProvider = new CArrayDataProvider($this->result, array(
            'sort' => array(
                'attributes' => array(
                    'ip',
                    'refer',
                    'time',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('hosts', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionFix() {
        $this->pageName = 'fix';
        
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );
        
        $sql = "SELECT tm,refer,ip,proxy,host,lang,user,req FROM {{surf}} WHERE (" . $this->_zfx . ") AND dt='" . $_GET['date'] . "' AND" . $this->_zp . " ORDER BY i DESC";
        $cmd = Yii::app()->db->createCommand($sql);

        foreach ($cmd->queryAll(false) as $row) {


            $ip = CMS::ip($row[2]);

            if ($row[3] != "") {
                $ip.= '<br>';
                $ip.= Html::link('через proxy', '?item=ip&qs=' . $row[3], array('target' => '_blank'));
            }


            $this->result[] = array(
                'time' => $row[0],
                'refer' => StatsHelper::renderReferer($row[1]),
                'ip' => $this->fixo("ip", $row[2]) . '' . $ip,
                'host' => $this->fixo("host", $row[4]) . '' . StatsHelper::getRowHost($row[2], $row[3], $row[4], $row[5]),
                'user_agent' => $this->fixo("user", $row[6]) . '' . StatsHelper::getRowUserAgent($row[6], $row[4]),
                'req' => $this->fixo("req", $row[7]) . '' . Html::link($row[7], $row[7]),
            );
        }





        $dataProvider = new CArrayDataProvider($this->result, array(
            'sort' => array(
                'attributes' => array(

                    'refer',
                    'req',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        //mysql_data_seek($r, 0);
        $n=array();
        foreach ($cmd->queryAll(false) as $row) {
            $n[] = strip_tags($this->fixo("refer", $row[1]));
            $n[] = strip_tags($this->fixo("ip", $row[2]));
            $n[] = strip_tags($this->fixo("host", $row[4]));
            $n[] = strip_tags($this->fixo("user", $row[6]));
            $n[] = strip_tags($this->fixo("req", $row[7]));
        }
        $nn = array_count_values($n);

        unset($nn[""]);


        $this->render('fix', array(
            'dataProvider' => $dataProvider,
            'count' => $nn
        ));
    }

}
