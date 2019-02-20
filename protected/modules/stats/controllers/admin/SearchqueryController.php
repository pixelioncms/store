<?php

class SearchqueryController extends CStatsController {

    public function actionIndex() {
        $engin = Yii::app()->request->getParam('engin');
        $pages = Yii::app()->request->getParam('pages');
        // $db = Yii::app()->db;
        //  if (isset($engin) or $top == 1) {
        if (isset($engin)) {
            switch ($engin) {
                case "Y": $e = "LOWER(refer) LIKE '%yand%'";
                    break;
                case "R": $e = "LOWER(refer) LIKE '%rambler.%'";
                    break;
                case "G": $e = "LOWER(refer) LIKE '%google.%'";
                    break;
                case "M": $e = "LOWER(refer) LIKE '%go.mail.ru%'";
                    break;
                case "H": $e = "LOWER(refer) LIKE '%search.yahoo%'";
                    break;
                case "S": $e = "LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%'";
                    break;
                case "?": $e = "LOWER(refer) LIKE '%?q=%' OR LOWER(refer) LIKE '%&q=%' OR LOWER(refer) LIKE '%query=%'";
                    break;
                default :
                    foreach ($se_nn as $key => $val)
                        if (stristr(strip_tags($key), strip_tags($engin))) {
                            $e = "LOWER(refer) LIKE '%$val%'";
                            break;
                        }
                    break;
            }
        } else
            $e = "LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%' OR LOWER(refer) LIKE '%?q=%' OR LOWER(refer) LIKE '%&q=%' OR LOWER(refer) LIKE '%query=%'" . $this->_cse_m;
        if ($this->sort == "hi") {
            $sql = "SELECT refer,req FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND ($e) AND LOWER(refer) NOT LIKE '%@%' AND" . $this->_zp;
        } else {
            $sql = "SELECT refer,req,ip FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND ($e) AND LOWER(refer) NOT LIKE '%@%' AND" . $this->_zp . " GROUP BY ip,refer";
        }
        $res = $this->db->createCommand($sql);
        // while ($row = $res->queryAll()) {
        $qmas = [];
        $results = $res->queryAll(false);
        if ($results) {
            foreach ($results as $row) {
                $refer = StatsHelper::Ref($row[0]);
                if (is_array($refer)) {
                    list($engine, $query) = $refer;
                    //   if ((strip_tags($engine) == $engin and $top == 4) or ( $top == 1))
                    if (!empty($str_f)) {
                        if (stristr($query, urldecode($str_f))) {
                            if (empty($query))
                                $query = '<span class="text-muted">неизвестно</span>';
                            $qmas[] = mb_strtolower($query, 'UTF-8');
                            if ($pages == 1)
                                $pmas[mb_strtolower($query, 'UTF-8')][] = $row[1];
                        }
                    }
                    else {
                        if (empty($query))
                            $query = '<span class="text-muted">неизвестно</span>';
                        $qmas[] = mb_strtolower($query, 'UTF-8');
                        if ($pages == 1)
                            $pmas[mb_strtolower($query, 'UTF-8')][] = $row[1];
                    }
                }
            }
            //if (!isset($qmas)) {
            // Yii::app()->tpl->alert('info','sad');
            //echo "<center>Нет данных2</center>";
            // die;
            //  }
            $newmas = array_count_values($qmas);
            arsort($newmas);
            $mmx = max($newmas);
            $cnt = array_sum($newmas);
            if ($pages == 1)
                foreach ($pmas as $key => $value) {
                    $pmas[$key] = array_count_values($pmas[$key]);
                    arsort($pmas[$key]);
                }
            //     echo "<table id=table align=center width=750 cellpadding=5 cellspacing=1 border=0><tr class=h><td width=40>№</td><td" . (($pages <> 1) ? " width=500" : " width=50%") . ">Поисковый запрос";
            if ($top == 4) {
                echo "&nbsp;<span style='background-color:#dcdcdc;'>&nbsp;&nbsp;";
                echo_se($engin);
                echo "&nbsp;&nbsp;</span>";
            }
            // echo (($pages <> 1) ? " <a class=e href=\"?" . str_replace(stristr($_SERVER['QUERY_STRING'], '&pages'), "", $_SERVER['QUERY_STRING']) . "&pages=1\">+</a>" : "") . "</td>" . (($pages <> 1) ? "" : "<td width=50%>Страницы <a class=e href=\"?" . str_replace(stristr($_SERVER['QUERY_STRING'], '&pages'), "", $_SERVER['QUERY_STRING']) . "&pages=0\">&ndash;</a></td>") . "<td width=50>" . (($this->sort == "hi") ? "Хиты" : "Хосты") . "</td><td width=100>График</td><td width=50>%</td></tr>";
            while (list($query, $val) = each($newmas)) {

                $k++;
                $vse += $val;
                // echo "<td>$k</td>";
                $queryData = Html::link($query, '?tz=6' . (isset($engin) ? "&engin=" . $engin : "") . '&pz=1&s_date=' . $this->sdate . '&f_date=' . $this->fdate . '&qs=' . htmlspecialchars($query) . '&sort=' . (empty($this->sort) ? "ho" : $this->sort), array('target' => '_blank'));
                //     echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'><a target=_blank href=\"?tz=6" . (isset($engin) ? "&engin=" . $engin : "") . "&pz=1&s_date=" . $this->sdate . "&f_date=" . $this->fdate . "&qs=" . htmlspecialchars($query) . "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">" . $query . "</a></td>";
                if ($pages == 1) {
                    echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'>";
                    foreach ($pmas[$query] as $ks => $v)
                        echo "<div><a href=" . $ks . " target=_blank>" . $ks . "</a></div>";
                    echo "</td>";
                }
                //     echo "<td>$val</td>";
                //    echo "<td><img align=left src=px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($val * 100) / $mmx) . " height=11 border=0></td>";
                //  echo "<td>" . (number_format((($val * 100) / $cnt), 1, ',', '')) . "</td></tr>";

                $this->result[] = array(
                    'num' => $k,
                    'query' => $queryData,
                    'val' => $val,
                    'progressbar' => $this->progressBar(ceil(($val * 100) / $mmx), number_format((($val * 100) / $cnt), 1, ',', ''), (($this->sort == "hi") ? "success" : "warning")),
                );
            }
            // echo "<tr class=h><td></td><td align=left><b>Всего:</b></td>" . (($pages <> 1) ? "" : "<td></td>") . "<td><b>$vse</b></td><td align=left>из $cnt</td><td></td></tr></table>";
        }

        $dataProvider = new CArrayDataProvider($this->result, array(
            'sort' => array(
                'attributes' => array(
                    'query',
                    'val',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionSystem() {
        $engin = Yii::app()->request->getParam('engin');
        $pages = Yii::app()->request->getParam('pages');
        //  if (isset($engin) or $top == 1) {
        if (isset($engin)) {
            switch ($engin) {
                case "Y": $e = "LOWER(refer) LIKE '%yand%'";
                    break;
                case "R": $e = "LOWER(refer) LIKE '%rambler.%'";
                    break;
                case "G": $e = "LOWER(refer) LIKE '%google.%'";
                    break;
                case "M": $e = "LOWER(refer) LIKE '%go.mail.ru%'";
                    break;
                case "H": $e = "LOWER(refer) LIKE '%search.yahoo%'";
                    break;
                case "S": $e = "LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%'";
                    break;
                case "?": $e = "LOWER(refer) LIKE '%?q=%' OR LOWER(refer) LIKE '%&q=%' OR LOWER(refer) LIKE '%query=%'";
                    break;
                default :
                    foreach ($se_nn as $key => $val)
                        if (stristr(strip_tags($key), strip_tags($engin))) {
                            $e = "LOWER(refer) LIKE '%$val%'";
                            break;
                        }
                    break;
            }
        } else {
            $e = "LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%' OR LOWER(refer) LIKE '%?q=%' OR LOWER(refer) LIKE '%&q=%' OR LOWER(refer) LIKE '%query=%'" . $this->_cse_m;
        }
        if ($this->sort == "hi") {
            $sql = "SELECT refer FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND (LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%' OR LOWER(refer) LIKE '%?q=%' OR LOWER(refer) LIKE '%&q=%' OR LOWER(refer) LIKE '%query=%'" . $this->_cse_m . ") AND LOWER(refer) NOT LIKE '%@%' AND" . $this->_zp;
        } else {
            $sql = "SELECT refer,ip FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND (LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%' OR LOWER(refer) LIKE '%?q=%' OR LOWER(refer) LIKE '%&q=%' OR LOWER(refer) LIKE '%query=%'" . $this->_cse_m . ") AND LOWER(refer) NOT LIKE '%@%' AND" . $this->_zp . " GROUP BY ip,refer";
        }
        $res = $this->db->createCommand($sql);
        // while ($row = mysql_fetch_row($res)) {
        $enmas = [];
        $results = $res->queryAll(false);
        if ($results) {
            foreach ($results as $row) {
                $refer = StatsHelper::Ref($row[0]);
                if (is_array($refer)) {
                    list($engine, $query) = $refer;
                    $enmas[] = $engine;
                }
            }
            // if (!isset($enmas)) {
            //     echo "<center>Нет данных</center>";
            //     die;
            // }
            $newmas = array_count_values($enmas);
            arsort($newmas);
            $mmx = max($newmas);
            $cnt = array_sum($newmas);
            // echo "<table id=table align=center width=750 cellpadding=5 cellspacing=1 border=0><tr class=h><td width=50>№</td><td width=440>Поисковая система</td><td width=50>" . (($sort == "hi") ? "Хиты" : "Хосты") . "</td><td width=100>График</td><td width=50>%</td><td width=50>Детали</td></tr>";
            while (list($engine, $val) = each($newmas)) {

                $k++;
                $vse += $val;

                //    echo "<td>$k</td><td align=left><a target=_blank href=\"?tz=6&engin=" . strip_tags($engine) . "&qs=allzz&pz=1&s_date=" . $this->sdate . "&f_date=" . $this->fdate . "&sort=" . (empty($sort) ? "ho" : $sort) . "\">";
                //  StatsHelper::echo_se($engine);
                //   echo "</a></td><td>$val</td>";
                //  echo "<td><img align=left src=px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($val * 100) / $mmx) . " height=11 border=0></td>";
                // echo "<td>" . (number_format((($val * 100) / $cnt), 1, ',', '')) . "</td>";
                //echo "<td><a class=d target=_blank href=\"?top=4&pos=10&engin=" . strip_tags($engine);
                //  if ($_GET['dy'])
                //      echo "&dy=" . $_GET['dy'];
                //   else if ($this->sdate)
                //     echo "&s_date=" . $this->sdate . "&f_date=" . $this->fdate;
                // echo "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">&gt;&gt;&gt;</a></td></tr>";

                $this->result[] = array(
                    'num' => $k,
                    'engine' => Html::link(StatsHelper::echo_se($engine), '?tz=6&engin=' . strip_tags($engine) . '&qs=allzz&pz=1&s_date=' . $this->sdate . '&f_date=' . $this->fdate . '&sort=' . (empty($this->sort) ? "ho" : $this->sort), array('target' => '_blank')),
                    'val' => $val,
                    'progressbar' => $this->progressBar(ceil(($val * 100) / $mmx), number_format((($val * 100) / $cnt), 1, ',', ''), (($this->sort == "hi") ? "success" : "warning")),
                );
            }
            // echo "<tr class=h><td></td><td align=left><b>Всего:</b></td><td><b>$vse</b></td><td align=left>из $cnt</td><td></td><td></td></tr></table>";
        }
        $dataProvider = new CArrayDataProvider($this->result, array(
            'sort' => array(
                // 'defaultOrder'=>'id ASC',
                'attributes' => array(
                    'engine',
                    'val',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('system', array(
            'dataProvider' => $dataProvider,
        ));
    }

}
