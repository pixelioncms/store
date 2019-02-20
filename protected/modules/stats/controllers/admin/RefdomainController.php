<?php

class RefdomainController extends CStatsController {

    //Переходы с доменов
    public function actionIndex() {
        $result = array();
        //  $stats = Yii::app()->stats;
        //  $s = $stats->initRun();
        $this->pageName = Yii::t('StatsModule.default', 'REF_DOMAIN');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );

        $sql = "SELECT refer,ip,req FROM {{surf}} WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND refer <> '' AND LOWER(refer) NOT LIKE '%://" . $this->_site . "%' AND LOWER(refer) NOT LIKE '" . $this->_site . "%' AND LOWER(refer) NOT LIKE '%://www." . $this->_site . "%' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%query=%'" . $this->_cot_m . ") AND" . $this->_zp . (($this->sort == "ho" or empty($this->sort)) ? "GROUP BY ip,refer" : "");
        // $res = mysql_query($z);
        $res = $this->db->createCommand($sql)->queryAll(false);
        if ($res) {
            foreach ($res as $row) {

                //while ($row = mysql_fetch_row($res)) {
                preg_match("/(?:[^:]+)*(?::\/\/)*(?:www.)*([^\/]+)/iu", $row[0], $m);
                //if (isset($str_f) and ! stristr($m[1], $str_f) and ! empty($str_f))
                //     continue;
                if (stristr($m[1], ":"))
                    continue;
                if ($this->sort != "hi")
                    if ($ot[$m[1]][$row[1]] != 1)
                        $ot[$m[1]][$row[1]] = 1;
                    else
                        continue;
                $othmas[] = $m[1];
                if ($_GET['pages'] == 1)
                    $pmas[$m[1]][] = $row[2];
            }
            //   if (!isset($othmas)) {
            //       echo "<center>Нет данных</center>";
            //  }
            $newmas = array_count_values($othmas);
            arsort($newmas);
            $mmx = max($newmas);
            $cnt = array_sum($newmas);
            if ($_GET['pages'] == 1)
                foreach ($pmas as $key => $value) {
                    $pmas[$key] = array_count_values($pmas[$key]);
                    arsort($pmas[$key]);
                }
            //   echo "<table id=table align=center width=750 cellpadding=5 cellspacing=1 border=0><tr class=h><td width=40>№</td><td nowrap" . (($_GET['pages'] <> 1) ? " width=500" : " width=50%") . ">Переходы с домена" . (($_GET['pages'] <> 1) ? " <a class=e href=\"?" . str_replace(stristr($_SERVER['QUERY_STRING'], '&pages'), "", $_SERVER['QUERY_STRING']) . "&pages=1\">+</a>" : "") . "</td>" . (($_GET['pages'] <> 1) ? "" : "<td width=50%>Страницы <a class=e href=\"?" . str_replace(stristr($_SERVER['QUERY_STRING'], '&pages'), "", $_SERVER['QUERY_STRING']) . "&pages=0\">&ndash;</a></td>") . "<td width=50>" . (($this->sort == "hi") ? "Хиты" : "Хосты") . "</td><td width=100>График</td></tr>";
            while (list($other, $val) = each($newmas)) {

                $k++;
                $vse += $val;

                // echo "<tr class=s1>";
                //  echo "<td>$k</td>";
                // echo "<a target=_blank href=\"?tz=3&pz=1&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . $other . "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">";
                if (stristr($other, "xn--")) {
                    $IDN = new idna_convert(array('idn_version' => 2008));
                    $dname = $IDN->decode($other);
                } else
                    $dname = $other;
                //  echo "</a>";

                $domain = Html::link($dname, "?tz=3&pz=1&s_date=" . $this->sdate . "&f_date=" . $this->fdate . "&qs=" . $other . "&sort=" . (empty($this->sort) ? "ho" : $this->sort));


                // if ($_GET['pages'] == 1) {
                //  echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'>";
                //   foreach ($pmas[$other] as $ks => $v)
                //      echo "<a href=\"" . $ks . "\" target=_blank>" . $ks . "</a><br>";
                //   echo "</td>";
                // }
                // echo "<td>$val</td>";
                /// echo "<td>" . $this->progressBar(ceil(($val * 100) / $mmx), (number_format((($val * 100) / $cnt), 1, ',', ''))) . "</td>";
                // echo "</tr>";


                $result[] = array(
                    'num' => $k,
                    'domains' => $domain,
                    'val' => $val,
                    'progressbar' => $this->progressBar(ceil(($val * 100) / $mmx), (number_format((($val * 100) / $cnt), 1, ',', ''))),
                );
            }
            // echo "<tr class=h><td></td><td align=left><b>Всего:</b></td>" . (($_GET['pages'] <> 1) ? "" : "<td></td>") . "<td><b>$vse</b></td><td align=left>из $cnt</td><td></td></tr></table>";
        }
        $dataProvider = new CArrayDataProvider($result, array(
            'sort' => array(
                // 'defaultOrder'=>'id ASC',
                'attributes' => array(
                    'domains',
                    'val'
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));



        $this->render('index', array('dataProvider' => $dataProvider));
    }

}
