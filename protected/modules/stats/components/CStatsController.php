<?php

class CStatsController extends AdminController {

    public $db;

    public function echo_se($engine) {
// global $se_n;
        switch ($engine) {
            case "Y": return "<b><font color=#FF0000>Я</font>ndex</b>";
                break;
            case "R": return "<b><font color=#0000FF>R</font>ambler</b>";
                break;
            case "G": return "<b><font color=#2159D6>G</font><font color=#C61800>o</font><font color=#D6AE00>o</font><font color=#2159D6>g</font><font color=#18A221>l</font><font color=#C61800>e</font></b>";
                break;
            case "M": return "<b><font color=#F8AC32>@</font><font color=#00468c>mail</font><font color=#F8AC32>.ru</font></b>";
                break;
            case "H": return "<b>Yahoo</b>";
                break;
            case "S": return "<b>MSN Bing</b>";
                break;
            case "?": return "<b>?</b>";
                break;
            default :
                foreach ($this->se_n as $key => $val)
                    if (stristr(strip_tags($key), strip_tags($engine))) {
                        return "<b>" . $key . "</b>";
                        break;
                    }
                break;
        }
    }

    public $topButtons = false;
    public $_zp; //zp
    public $_zp2; //zp2
    public $_cse_m = ''; //$cse_m
    public $_cot_m = ''; //cot_m
    public $_site; //site
    public $_zfx; //$zfx
    public $rbd;
    public $rbdn;
    public $robo;
    public $se_n;
    public $result = array();
    public $hbdn;
    public $fx_m;

    public function fixo($s, $str) {
        // global $fx_m;
        foreach ($this->fx_m as $vl) {
          //  list($s1, $s2, $s3) = explode("|", $vl);
            $vl[2] = trim($vl[2]);
            if (stristr($str, $vl[1]) and ! empty($vl[2]) and $s == $vl[0])
                return "<font color='#DE3163'><b>" . $vl[2] . " :</b></font><br>";
        }
        return;
    }
    /*
     public function fixo($s, $str) {
        foreach ($this->fx_m as $vl) {
            list($s1, $s2, $s3) = explode("|", $vl);
            $s3 = trim($s3);
            if (stristr($str, $s2) and ! empty($s3) and $s == $s1)
                return "<font color='#DE3163'><b>" . $s3 . " :</b></font><br>";
        }
        return;
    }*/

    protected function progressBarStack($width = array(), $value = array(), $class = array(), $num = 2) {
        $content = '<div class="progress">';
        for ($x = 0; $x <= $num - 1; $x++) {
            $content .='<div class="progress-bar progress-bar-' . $class[$x] . '" style="width: ' . $width[$x] . '%">
            </div>';
        }
        $content .='</div>';
        return $content;
    }

    protected function progressBar($width, $value, $class = 'info') {
        return '<div class="progress">
            <div class="progress-bar progress-bar-' . $class . '" role="progressbar" aria-valuenow="' . $width . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $width . '%;">
            ' . $value . '%</div></div>';
    }

    public function init() {
        $this->db = Yii::app()->db;
        //   list($s_date, $f_date) = str_replace("+", "", array($this->sdate, $this->fdate));

        //if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' || $_SERVER['REMOTE_ADDR'] !== '195.78.247.104') {
        //    throw new CHttpException(401);
       // }
        if (!preg_match("/^[0-9]{4}-([0-9]{2})-([0-9]{2})$/", $this->fdate) && !preg_match("/^[0-9]{4}-([0-9]{2})-([0-9]{2})$/", $this->sdate)) {
            throw new CException('Не верный формат даты!');
        }
        if ($robots = file(Yii::getPathOfAlias('mod.stats') . "/robots.dat")) {
            $i = 0;
            for ($i = 0; $i < count($robots); $i++)
                $robots[$i] = iconv("CP1251", "UTF-8", $robots[$i]);
            foreach ($robots as $val) {
                list($rb1, $rb2) = explode("|", $val);
                $rb2 = trim($rb2);
                $this->rbd[$i++] = rtrim($rb1);
                if (!empty($rb2))
                    $this->rbdn[$rb2][] = rtrim($rb1);
                $robo[] = $rb2;
            }
        }
        if ($hosts = file(Yii::getPathOfAlias('mod.stats') . "/hosts.dat")) {
            $i = 0;
            for ($i = 0; $i < count($hosts); $i++)
                $hosts[$i] = iconv("CP1251", "UTF-8", $hosts[$i]);
            foreach ($hosts as $val) {
                list($hb1, $hb2) = explode("|", $val);
                $hb2 = trim($hb2);
                $hbd[$i++] = rtrim($hb1);
                if (!empty($hb2))
                    $this->hbdn[$hb2][] = rtrim($hb1);
                $robo[] = $hb2;
            }
        }
        $this->robo = array_unique($robo);

        foreach ($this->rbd as $val)
            $this->_zp .= " LOWER(user) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
        if (filesize(Yii::getPathOfAlias('mod.stats') . "/hosts.dat"))
            foreach ($hbd as $val)
                $this->_zp .= " LOWER(host) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
        $this->_zp .= " LOWER(user) NOT LIKE '' AND";
        if (file_exists(Yii::getPathOfAlias('mod.stats') . "/skip.dat")) {
            if ($skip = file(Yii::getPathOfAlias('mod.stats') . "/skip.dat")) {
                foreach ($skip as $vl) {
                    list($s1, $s2) = explode("|", $vl);
                    $this->_zp2 .= " $s1 NOT LIKE '%" . rtrim($s2) . "%' AND";
                }
            }
        }

        $this->_zp .= $this->_zp2;
        $this->_zp = substr($this->_zp, 0, -4);

        if ($se_m = file(Yii::getPathOfAlias('mod.stats') . "/se.dat")) {
            for ($i = 0; $i < count($se_m); $i++)
                $se_m[$i] = iconv("CP1251", "UTF-8", $se_m[$i]);
            foreach ($se_m as $vl) {
                list($s1, $s2, $s3) = explode("|", $vl);
                $this->se_n[$s1] = rtrim($s3);
                $se_nn[$s1] = $s2;
            }
        }
        $newFixArray = array(
          array('ip','195.78.247.104','Мой айпи')
        );
        
        if (count($newFixArray)>0) {
            $this->fx_m = $newFixArray;
                $this->_zfx = "";
                $pf = "";
                for ($i = 0; $i < count($this->fx_m); $i++){
                    $this->fx_m[$i] = $this->fx_m[$i];
                }
                foreach ($this->fx_m as $obj) {
                    $this->_zfx .= $pf . "LOWER(" . $obj[0] . ") LIKE '%" . mb_strtolower($obj[1]) . "%'";
                    $pf = " OR ";
                    $obj[2] = rtrim($obj[2]);
                    if (!empty($obj[2]))
                        $fxn[$obj[2]][] = $obj[0] . "|" . $obj[1];
                    $fxo[] = $obj[2];
                }
        }
       /* if (file_exists(Yii::getPathOfAlias('mod.stats') . "/fix.dat")) {
            if ($this->fx_m = file(Yii::getPathOfAlias('mod.stats') . "/fix.dat")) {
                $this->_zfx = "";
                $pf = "";
                for ($i = 0; $i < count($this->fx_m); $i++)
                    $this->fx_m[$i] = iconv("CP1251", "UTF-8", $this->fx_m[$i]);
                foreach ($this->fx_m as $vl) {
                    list($s1, $s2, $s3) = explode("|", $vl);
                    $this->_zfx .= $pf . "LOWER(" . $s1 . ") LIKE '%" . mb_strtolower($s2) . "%'";
                    $pf = " OR ";
                    $s3 = rtrim($s3);
                    if (!empty($s3))
                        $fxn[$s3][] = $s1 . "|" . $s2;
                    $fxo[] = $s3;
                }
            }
        }*/

        foreach ($se_nn as $val) {
            $this->_cse_m .= " OR LOWER(refer) LIKE '%$val%'";
            $this->_cot_m .= " AND LOWER(refer) NOT LIKE '%$val%'";
        }

        /* $pages = $_GET['pages'];
          if ($pages == "0" and file_exists("pages.dat"))
          unlink("pages.dat");
          if ($pages == "1") {
          $fp = fopen("pages.dat", "w");
          fwrite($fp, "1");
          fclose($fp);
          }
          if (file_exists("pages.dat"))
          $pages = 1; */

        // $this->_site = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        $this->_site = str_replace("www.", "", Yii::app()->request->serverName);
        parent::init();
    }

    /* public function c_other($dt) {
      $sql = "SELECT COUNT(refer) FROM cms_surf WHERE dt='" . $dt . "' AND refer <> '' AND LOWER(refer) NOT REGEXP '^(ftp|http|https):\/\/(www.)*" . $this->_site . "' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%query=%'" . $this->_cot_m . ") AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      $res = $command->queryRow(false);
      return $res[0];
      }

      public function c_fix($dt) {
      $sql = "SELECT COUNT(i) FROM cms_surf WHERE (" . $this->_zfx . ") AND dt='" . $dt . "' AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      $res = $command->queryRow(false);
      return $res[0];
      }

      public function c_se($dt) {
      $sql = "SELECT COUNT(refer) FROM cms_surf WHERE dt='" . $dt . "' AND (LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%'" . $this->_cse_m . ") AND LOWER(refer) NOT LIKE '%@%' AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      $res = $command->queryRow(false);
      return $res[0];
      }

      public function c_uniqs_hits($dt) {
      $this->
      $sql = "SELECT COUNT(DISTINCT ip),COUNT(i) FROM cms_surf WHERE dt='" . $dt . "' AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      return $command->queryRow(false);
      } */

    public function Ref($ref) {

        if (($ref != "") and ! (stristr($ref, "://" . $this->_site) and stripos($ref, "://" . $this->_site, 6) == 0) and ! (stristr($ref, "://www." . $this->_site) and stripos($ref, "://www." . $this->_site, 6) == 0)) {

            $reff = str_replace("www.", "", $ref);
            if (!stristr($ref, "://")) {
                $reff = "://" . $reff;
                $ref = "://" . $ref;
            }
            if (stristr($reff, "://yandex") or stristr($reff, "://search.yaca.yandex") or stristr($reff, "://images.yandex"))
                return StatsHelper::se_yandex($ref);
            else
            if (stristr($reff, "://google"))
                return StatsHelper::se_google($ref);
            else
            if (stristr($reff, "://rambler") or stristr($reff, "://nova.rambler") or stristr($reff, "://search.rambler") or stristr($reff, "://ie4.rambler") or stristr($reff, "://ie5.rambler"))
                return StatsHelper::se_rambler($ref);
            else
            if (stristr($reff, "://go.mail.ru") and stristr($reff, "words="))
                return StatsHelper::se_mail1($ref);
            else
            if (stristr($reff, "://go.mail.ru") or stristr($reff, "://wap.go.mail.ru"))
                return StatsHelper::se_mail2($ref);
            else
            if (stristr($reff, "://search.msn") or stristr($reff, "://search.live.com") or stristr($reff, "://ie.search.msn") or stristr($reff, "://bing"))
                return StatsHelper::se_msn($ref);
            else
            if (stristr($reff, "://search.yahoo"))
                return StatsHelper::se_yahoo($ref);
            else
            if (StatsHelper::se_sp($ref) <> -1)
                return StatsHelper::se_sp($ref);
            else
            if (stristr($ref, "?q=") or stristr($ref, "&q="))
                return se_other($ref, "q=");
            else
            if (stristr($ref, "query="))
                return se_other($ref, "query=");
            else
                return $ref;
        } else
            return $ref;
    }

    public function is_robot($check, $check2) {

        if (empty($check))
            return TRUE;
        if (isset($this->rbd))
            foreach ($this->rbd as $val)
                if (stristr($check, $val))
                    return TRUE;
        if (isset($hbd))
            foreach ($hbd as $val)
                if (stristr($check2, $val))
                    return TRUE;
        return FALSE;
    }

    public function timefilter($sort = true) {
        //  global $s_date, $f_date, $u;


        $sql = "SELECT DISTINCT dt FROM {{surf}} ORDER BY 1 DESC";
        $command = $this->db->createCommand($sql);



        //$res = mysql_query("SELECT DISTINCT dt FROM cms_surf ORDER BY 1 DESC");
        if (Yii::app()->request->getParam('dy'))
            switch (Yii::app()->request->getParam('dy')) {
                case 1:
                    $cmd = $command->queryRow();
                    $s_date = $cmd['dt'];
                    $f_date = $s_date;
                    break;
                case 2:
                    $cmd = $command->queryRow();
                    $s_date = $cmd['dt'];
                    $f_date = $s_date;
                    break;
                case 3:
                    $f_date = mysql_result($res, 0);
                    $s_date = substr($f_date, 0, 6) . "01";
                    break;
                case 4:
                    $cmd = $command->queryRow();
                    $f_date = $cmd['dt'];
                    //die($f_date);
                    //$f_date = mysql_result($res, 0);
                    $f_d = substr($f_date, 0, 4) . substr($f_date, 4, 2);
                    //while ($row = mysql_fetch_row($res)) {
                    foreach ($command->queryAll() as $row) {
                        $s_d = substr($row['dt'], 0, 4) . substr($row['dt'], 4, 2);
                        if ($f_d <> $s_d) {
                            $f_date = $row['dt'];
                            break;
                        }
                    }
                    $s_date = $s_d . "01";
                    break;
                case 5:
                    $s_date = mysql_result($res, mysql_num_rows($res) - 1);
                    $f_date = mysql_result($res, 0);
                    break;
            }
        if (empty($s_date)) {
            $cmd = $command->queryRow();
            $s_date = $cmd['dt'];
        }
        if (empty($f_date)) {
            $cmd = $command->queryRow();
            $f_date = $cmd['dt'];
        }

        $this->renderPartial('stats.views.admin.default._filters', array(
            'sort' => $sort
        ));
    }

    public function setSdate($date) {
        $this->sdate = $date;
    }

    public function setFdate($date) {
        $this->fdate = $date;
    }

    public function getSdate() {
        $sdate = Yii::app()->request->getParam('s_date');
        return ($sdate) ? $sdate : date('Y-m-d');
    }

    public function getFdate() {
        $fdate = Yii::app()->request->getParam('f_date');
        return ($fdate) ? $fdate : date('Y-m-d');
    }

    public function getBwr() {
        return Yii::app()->request->getParam('bwr');
    }

    public function getSort() {
        return Yii::app()->request->getParam('sort');
    }

    public function getPos() {
        return Yii::app()->request->getParam('pos');
    }

}
