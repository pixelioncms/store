<?php

class RobotsController extends CStatsController {

    public function actionIndex() {
        $this->pageName = Yii::t('StatsModule.default', 'ROBOTS');
        foreach ($this->robo as $val) {
            $zs = "";
            $pf = "";
            if (empty($val))
                continue;
            if (isset($this->rbdn[$val]))
                foreach ($this->rbdn[$val] as $vl) {
                    $zs .= $pf . "LOWER(user) LIKE '%" . mb_strtolower($vl) . "%'";
                    $pf = " OR ";
                }
            if (isset($this->hbdn[$val]))
                foreach ($this->hbdn[$val] as $vl) {
                    $zs .= $pf . "LOWER(host) LIKE '%" . mb_strtolower($vl) . "%'";
                    $pf = " OR ";
                }


            $sql = "SELECT COUNT(i) as count, MAX(i) as count_max FROM {{surf}} WHERE (" . $zs . ") AND " . $this->_zp2 . " dt >= '$this->sdate' AND dt <= '$this->fdate'";
            $cmd = $this->db->createCommand($sql);

            $r = $cmd->queryRow();

            $d = $r['count_max'];

            $cnt[$val] = $r['count'];
            if ($cnt[$val] > 0) {
                $z2 = "SELECT dt,tm FROM {{surf}} WHERE i = " . $d;
                $cmd2 = $this->db->createCommand($z2);
                $r2 = $cmd2->queryRow();
         
             $ff_date[$val] = $r2['dt'] . " &nbsp;<font color='#de3163'>" . $r2['tm'] . "</font>";
            }else{
                 $ff_date[$val] = "0 &nbsp;<font color='#de3163'>0</font>";
            }
        }
        arsort($cnt);
        $mmx = max($cnt);
        $cn = array_sum($cnt);
        $result = array();
        $k=0;
        foreach ($cnt as $val => $co) {
            if ($co <> 0) {
                $k++;



                $result[] = array(
                    'num' => $k,
                    'bot' => '<a target=_blank href="robots/detail?s_date=' . $this->sdate . '&f_date=' . $this->fdate . '&qs=' . $val . '">' . $val . '</a>',
                    'visit' => $ff_date[$val],
                    'count' => $co,
                    'progressbar' => $this->progressBar(ceil(($co * 100) / $mmx), number_format((($co * 100) / $cn), 1, '.', '')),
                    'detail' => StatsHelper::linkDetail("robots/detail?s_date={$this->sdate}&f_date={$this->fdate}&qs={$val}")
                );
                ?>

                <?php

            }
        }

        $dataProvider = new CArrayDataProvider($result, array(
            'sort' => array(
                'attributes' => array(
                    'bot',
                    'visit',
                    'count'
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'cnt' => $cnt,
            'cn' => $cn,
            'ff_date' => $ff_date,
            'mmx' => $mmx,
        ));
    }

    public function actionDetail() {
        $qs = $_GET['qs'];
        $this->pageName = Yii::t('StatsModule.default', 'ROBOTS');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName => array('/admin/stats/robots'),
            $qs
        );



        $zs = "";
        $pf = "";
        if (isset($this->rbdn[$qs]))
            foreach ($this->rbdn[$qs] as $vl) {
                $zs .= $pf . "LOWER(user) LIKE '%" . mb_strtolower($vl) . "%'";
                $pf = " OR ";
            }
        if (isset($this->hbdn[$qs]))
            foreach ($this->hbdn[$qs] as $vl) {
                $zs .= $pf . "LOWER(host) LIKE '%" . mb_strtolower($vl) . "%'";
                $pf = " OR ";
            }
        // $res = mysql_query("SELECT day,dt,tm,refer,ip,proxy,host,lang,user,req FROM " . $tablePref . "surf WHERE (" . $zs . ") AND " . $zp2 . " dt >= '$s_date' AND dt <= '$f_date' ORDER BY i DESC");
        $sql = "SELECT day,dt,tm,refer,ip,proxy,host,lang,user,req FROM {{surf}} WHERE (" . $zs . ") AND " . $this->_zp2 . " dt >= '$this->sdate' AND dt <= '$this->fdate' ORDER BY i DESC";
        $cmd = $this->db->createCommand($sql);

        $r = $cmd->queryAll();
        $this->render('detail', array(
            'items' => $r,
        ));
    }

}
