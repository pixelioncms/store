<?php

class DefaultController extends CStatsController {

    public function actionIndex() {
        $this->pageName = Yii::t('StatsModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/stats'),
            $this->pageName
        );
        $result = array();


        $stats = Yii::app()->stats;
        $s = $stats->initRun();
        foreach (StatsMainHistory::model()->findAll(array('order' => '`t`.`i` ASC')) as $rw) {

            $dt_i = $rwz["dt"][] = $rw->dt;
            $rwz["hosts"][$dt_i] = $rw->hosts;
            $rwz["hits"][$dt_i] = $rw->hits;
            $rwz["search"][$dt_i] = $rw->search;
            $rwz["other"][$dt_i] = $rw->other;
            $rwz["fix"][$dt_i] = $rw->fix;
        }

        foreach (StatsMainp::model()->findAll() as $rww) {
            $dt_i = $rww["dt"] . $rww->god;
            $rwzz[$dt_i]["hosts"] = $rww->hosts;
            $rwzz[$dt_i]["hits"] = $rww->hits;
            $rwzz[$dt_i]["search"] = $rww->search;
            $rwzz[$dt_i]["other"] = $rww->other;
            $rwzz[$dt_i]["fix"] = $rww->fix;
        }


        $c = 0;
        $all_uniqs = 0;
        $all_hits = 0;
        $all_se = 0;
        $all_other = 0;
        $all_fix = 0;
        $sdate = 0;





        $r = $this->db->createCommand();
        $r->selectDistinct('day, dt');
        $r->from('{{surf}}');
        $r->order('i DESC');
        $res = $r->queryRow(false);

        $fdate = date('Y-m-d');



        foreach ($r->queryAll() as $dtm) {
            if (substr($sdate, 4, 2) <> substr($dtm['dt'], 4, 2) && $sdate <> 0)
                $c++;
            $sdate = $dtm['dt'];
            if ($dtm['dt'] != $fdate && !empty($rwz["hits"][$dtm['dt']])) {
                $m_uniqs[$dtm['dt']] = $rwz["hosts"][$dtm['dt']];
                $m_hits[$dtm['dt']] = $rwz["hits"][$dtm['dt']];
            } else {

                //die(print_r($this->c_uniqs_hits($dtm[1]['dt'])));
                list($m_uniqs[$dtm['dt']], $m_hits[$dtm['dt']]) = $stats->countVisits($dtm['dt']);
            }
        }
        
        $sdate = $res[1]; //dt
        $i = 0;
//mysql_data_seek($r, 0);



        $max_hits = max($m_hits);

        foreach ($r->queryAll() as $row) {

            $dt = $row['dt'];
            if ($dt != $fdate && isset($rwz["search"][$dt])) {
                $m_se[$dt] = $rwz["search"][$dt];
                $m_other[$dt] = $rwz["other"][$dt];
                $m_fix[$dt] = $rwz["fix"][$dt];
            } else {

                $m_se[$dt] = $stats->countSearchEngine($dt);
                $m_other[$dt] = $stats->countOther($dt);
                if ($stats->fx) {
                    $m_fix[$dt] = $stats->countFix($dt);
                } else {
                    $m_fix[$dt] = 0;
                }

                if (isset($rwz)) {

                if ($dt != $fdate && !in_array($dt, $rwz["dt"])) {
                    // die('save');
                    $sql_insert = "INSERT INTO {{main_history}}(dt,hosts,hits,search,other,fix) VALUES('" . $dt . "','" . $m_uniqs[$dt] . "','" . $m_hits[$dt] . "','" . $m_se[$dt] . "','" . $m_other[$dt] . "','" . $m_fix[$dt] . "')";
                    $this->db->createCommand($sql_insert)->execute();

                    $sql_del = "DELETE me FROM {{main_history}} as me, {{main_history}} as clone WHERE me.dt = clone.dt AND me.i > clone.i";
                    $this->db->createCommand($sql_del)->execute();

                    //mysql_query("DELETE me FROM mainh as me, mainh as clone WHERE me.dt = clone.dt AND me.i > clone.i");
                }
                 }
            }


            if ($m_uniqs[$dt] == $m_hits[$dt]) //если хосты и хиты равны!
                $graphic = $this->progressBar(ceil((372 * $m_uniqs[$dt]) / $max_hits), ceil((372 * $m_uniqs[$dt]) / $max_hits));
            else
                $graphic = $this->progressBarStack(
                        array(
                    round((100 * $m_uniqs[$dt]) / $max_hits),
                    ceil((100 * $m_hits[$dt]) / $max_hits) - ceil((100 * $m_uniqs[$dt]) / $max_hits) - 1
                        ), array(
                    $m_uniqs[$dt],
                    $m_hits[$dt]
                        ), array('success', 'warning'));



            $result[] = array(
                'date' => StatsHelper::$DAY[$row['day']] . $dt,
                'graphic' => $graphic,
                //'hosts' => Html::link($m_uniqs[$dt], '/admin/stats/detail/hosts?date=' . $dt),
                'hosts' => Html::link($m_uniqs[$dt], array('/admin/stats/detail/hosts', 'date' => $dt)),
                'hits' => Html::link($m_hits[$dt], array('/admin/stats/detail/hits', 'date' => $dt)),
                'search' => Html::link($m_se[$dt], array('/admin/stats/detail/search', 'date' => $dt)),
                'sites' => Html::link($m_other[$dt], array('/admin/stats/detail/other', 'date' => $dt)),
                'fix' => Html::link($m_fix[$dt], array('/admin/stats/detail/fix', 'date' => $dt))
            );
        }



       // if(file_exists(Yii::getPathOfAlias('mod.stats') . "/total.dat")){
        //if ($total = file(Yii::getPathOfAlias('mod.stats') . "/total.dat"))
       //     $total = explode("|", $total[0]);
       // }

        $uniq_total = $this->db->createCommand("SELECT COUNT(DISTINCT ip) as total FROM {{surf}} WHERE " . $this->_zp);

        $all_uniqs = $uniq_total->queryRow(false);






        $dday = array(0 => "ВС", 1 => "ПН", 2 => "ВТ", 3 => "СР", 4 => "ЧТ", 5 => "ПТ", 6 => "СБ");

        $dataProvider = new CArrayDataProvider($result, array(
   
            'sort' => array(
                'attributes' => array(
                    'hosts',
                    'hits',
                    'graphic',
                    'search',
                    'sites',
                    'date',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        $m_date = array_keys($m_hits);
        $mmx = max($m_hits);
        $weekResult = array();

        //$weekResult=  array_slice($weekResult['hits'],7);

        for ($i = 0; $i < count($m_hits); $i++) {
            $d = $m_date[$i];
            $weekResult['hits'][] = (int) $m_hits[$d];
            $weekResult['hosts'][] = (int) $m_uniqs[$d];
            $weekResult['cats'][] = date('m.d',strtotime($d)).' '.$dday[date('w', strtotime($d))];
            //echo key($dday[date('w', strtotime($d))]);
            if($i==7) break;
        }



        $this->render('index', array(
            'weekResult' => $weekResult,
            'dataProvider' => $dataProvider,
            'm_date' => $m_date,
            'mmx' => $mmx,
            'all_uniqs' => $all_uniqs[0],
            'total_hits' => (array_sum($m_hits)),
            'total_search' => (array_sum($m_se)),
            'total_other' => (array_sum($m_other)),
        ));
    }

    /**
      public function actionDetail($detail) {
      $group = $_GET['group'];
      if (file_exists("detail.dat") and $group <> "false")
      $group = "true";
      if ($group <> "true") {
      echo "<table id=table align=center width=100% cellpadding=5 cellspacing=1 border=0><tr class=h><td width=35>Время</td><td>Referer</td><td width=90>IP-адрес <a class=d href=\"?detail=" . $detail . "&group=true\"\"\">&plusmn;</a></td><td>Хост</td><td>User-Agent</td><td>Страница</td></tr>";
      // $r = mysql_query("SELECT tm,refer,ip,proxy,host,lang,user,req FROM cms_surf WHERE dt='" . $detail . "' ORDER BY i DESC");


      $sql = "SELECT tm,refer,ip,proxy,host,lang,user,req FROM cms_surf WHERE dt='" . $detail . "' ORDER BY i DESC";
      $command = Yii::app()->db->createCommand($sql);
      foreach ($command->queryAll() as $row) {

      //while ($row = mysql_fetch_row($r)) {
      if ($s == "s2") {
      $s = "s1";
      echo "<tr class=s1>";
      } else {
      $s = "s2";
      echo "<tr class=s2>";
      }
      echo "<td>" . $row['tm'] . "</td>";

      echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'>";
      $refer = $this->Ref($row['refer']);
      if (is_array($refer)) {
      list($engine, $query) = $refer;
      if ($engine == "G" and ! empty($query) and stristr($row['refer'], "/url?"))
      $row['refer'] = str_replace("/url?", "/search?", $row['refer']);
      echo_se($engine);
      if (empty($query))
      $query = "<font color=grey>неизвестно</font>";
      echo ": <a target=_blank href=\"" . $row['refer'] . "\">" . $query . "</a></td>";
      } else if ($refer == "")
      echo "<font color=grey>неизвестно</font>";
      else {
      echo "<a target=_blank href=\"" . $row['refer'] . "\">";
      if (stristr(urldecode($row['refer']), "xn--")) {
      $IDN = new idna_convert(array('idn_version' => 2008));
      echo $IDN->decode(urldecode($row['refer']));
      } else
      echo urldecode($row['refer']);
      echo "</a></td>";
      }

      if ($row['ip'] != "unknown")
      echo "<td><a target=_blank href=\"?item=ip&qs=" . $row['ip'] . "\">" . $row['ip'] . "</a>";
      else
      echo "<td><font color=grey>неизвестно</font>";
      if ($row['proxy'] != "")
      echo "<br><a target=_blank href=\"?item=ip&qs=" . $row['proxy'] . "\">через proxy</a>";
      echo "</td>";

      if ($row['host'] == "")
      echo "<td><font color=grey>неизвестно</font>";
      else
      echo "<td><a target=_blank href=\"http://www.tcpiputils.com/browse/ip-address/" . (($row['proxy'] != "") ? $row['proxy'] : $row['ip']) . "\">" . $row['host'] . "</a>";
      if ($row['lang'] != "") {
      echo "<br>Язык: " . (!empty(StatsHelper::$LANG[mb_strtoupper($row['lang'])]) ? StatsHelper::$LANG[mb_strtoupper($row['lang'])] : "<font color=grey>неизвестно</font>");
      if (file_exists("/stats/flags/" . mb_strtolower(StatsHelper::$LANG[mb_strtoupper($row['lang'])]) . ".gif"))
      echo Html::image('/uploads/language/' . mb_strtolower(StatsHelper::$LANG[$row['lang']]) . '.gif'); //" <img align=absmiddle src=/stats/flags/" . mb_strtolower(StatsHelper::$LANG[mb_strtoupper($row['lang'])]) . ".gif width=16 height=12>";
      }
      echo "</td>";

      echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'>";
      if (!$this->is_robot($row['user'], $row['host'])) {
      $brw = StatsHelper::getBrowser($row['user']);
      if ($brw != "")
      echo "<img src=/stats/browsers/$brw width=16 height=16 align=absmiddle> ";
      }
      echo $row['user'] . "</td>";
      echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;' nowrap><a target=_blank href=" . $row['req'] . ">" . $row['req'] . "</a></td>";
      echo "</tr>";
      }
      echo "<tr class=h><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>";
      //norm(0);
      } else {
      echo "<table id=table align=center width=100% cellpadding=5 cellspacing=1 border=0><tr class=h><td width=90>IP-адрес <a class=d href=\"?detail=" . $detail . "&group=false\"\"\">&plusmn;</a></td><td>Хост</td><td>User-Agent</td><td width=30%>Referer</td><td width=35>Время</td><td>Страница</td></tr>";
      $sql = "SELECT tm,refer,ip,proxy,host,lang,user,req FROM cms_surf WHERE dt='" . $detail . "' ORDER BY i DESC";
      $command = Yii::app()->db->createCommand($sql);
      foreach ($command->queryAll() as $r) {
      //print_r($r);
      //die;
      //$rs = mysql_query("SELECT tm,refer,ip,proxy,host,lang,user,req FROM cms_surf WHERE dt='" . $detail . "' ORDER BY i DESC");
      // while ($r = mysql_fetch_row($rs))
      $row[$r['ip']][] = array($r['tm'], $r['refer'], $r['ip'], $r['proxy'], $r['host'], $r['lang'], $r['user']);
      foreach ($row as $ip => $val) {
      if ($s == "s2") {
      $s = "s1";
      echo "<tr class=s1>";
      } else {
      $s = "s2";
      echo "<tr class=s2>";
      }

      if ($ip != "unknown")
      echo "<td rowspan=" . count($val) . "><a target=_blank href=\"?item=ip&qs=" . $ip . "\">" . $ip . "</a>";
      else
      echo "<td><font color=grey>неизвестно</font>";
      if ($val[0][2] != "")
      echo "<br><a target=_blank href=\"?item=ip&qs=" . $val[0][2] . "\">через proxy</a>";
      echo "</td>";
      $skip = 0;
      foreach ($val as $k => $rw) {
      if ($skip <> 0)
      echo "<tr class=" . $s . ">";
      $skip = 1;

      if ($rw[3] == "")
      echo "<td><font color=grey>неизвестно</font>";
      else
      echo "<td><a target=_blank href=\"http://www.tcpiputils.com/browse/ip-address/" . (($rw[2] != "") ? $rw[2] : $ip) . "\">" . $rw[3] . "</a>";
      if ($rw[4] != "") {
      echo "<br>Язык: " . (!empty(StatsHelper::$LANG[mb_strtoupper($rw[4])]) ? $lang[mb_strtoupper($rw[4])] : "<font color=grey>неизвестно</font>");
      if (file_exists("flags/" . mb_strtolower(StatsHelper::$LANG[mb_strtoupper($rw[4])]) . ".gif"))
      echo " 123132<img align=absmiddle src=flags/" . mb_strtolower(StatsHelper::$LANG[mb_strtoupper($rw[4])]) . ".gif width=16 height=12>";
      }
      echo "</td>";

      echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'>";
      if (!$this->is_robot($rw[5], $rw[3])) {
      $brw = StatsHelper::getBrowser($rw[5]);
      if ($brw != "")
      echo "<img src=browsers/$brw width=16 height=16 align=absmiddle> ";
      }
      echo $rw[5] . "</td>";

      echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'>";
      $refer = $this->Ref($rw[1]);
      if (is_array($refer)) {
      list($engine, $query) = $refer;
      if ($engine == "G" and ! empty($query) and stristr($rw[1], "/url?"))
      $rw[1] = str_replace("/url?", "/search?", $rw[1]);
      echo_se($engine);
      if (empty($query))
      $query = "<font color=grey>неизвестно</font>";
      echo ": <a target=_blank href=\"" . $rw[1] . "\">" . $query . "</a></td>";
      } else if ($refer == "")
      echo "<font color=grey>неизвестно</font>";
      else {
      echo "<a target=_blank href=\"" . $row[1] . "\">";
      if (stristr(urldecode($rw[1]), "xn--")) {
      $IDN = new idna_convert(array('idn_version' => 2008));
      echo $IDN->decode(urldecode($rw[1]));
      } else
      echo urldecode($rw[1]);
      echo "</a></td>";
      }

      echo "<td>" . $rw[0] . "</td>";
      echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;' nowrap><a target=_blank href=" . $rw[6] . ">" . $rw[6] . "</a></td>";
      echo "</tr>";
      }
      }
      }
      echo "<tr class=h><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>";
      }
      }
     */
    public function actionLast() {
        $getSite = Yii::app()->request->getParam('site');
        $getQuery = Yii::app()->request->getParam('query');
        if (isset($getSite)) {
            $sql = "SELECT refer,day,dt,tm,req FROM {{surf}} WHERE refer <> '' AND LOWER(refer) NOT LIKE '%://" . $this->_site . "%' AND LOWER(refer) NOT LIKE '%://www." . $this->_site . "%' AND LOWER(refer) NOT LIKE '" . $this->_site . "%' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%query=%'" . $this->_cot_m . ") AND " . $this->_zp . " ORDER BY i DESC LIMIT " . $getSite;
            $cmd = $this->db->createCommand($sql);
            $this->render('last_site', array('items' => $cmd->queryAll(), 'n' => $getSite));
        } elseif (isset($getQuery)) {
            $sql = "SELECT refer,day,dt,tm,req FROM {{surf}} WHERE (LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%'" . $this->_cse_m . ") AND LOWER(refer) NOT LIKE '%@%' AND " . $this->_zp . " ORDER BY i DESC LIMIT " . $getQuery;
            $cmd = $this->db->createCommand($sql);
            $this->render('last_query', array('items' => $cmd->queryAll(), 'n' => $getQuery));
        } else {
            throw new CHttpException(404);
        }
    }

}
