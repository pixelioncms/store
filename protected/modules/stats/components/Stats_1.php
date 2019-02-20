<?php

// change table name "surf" to "collector"
Yii::import('mod.stats.models.*');

class Stats extends CApplicationComponent {

    //  protected $fx;
    public $_zp;
    public $rbd;
    public $hbd;
    public $robo;
    public $_cse_m;
    public $_cot_m = null;
    public $_zfx = '';
   // public $_site;

    // protected $today, $dt;

    public function getFx() {
        if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
            return true;
        } else {
            return false;
        }
    }

    public function getSite() {
      //  return str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        return str_replace("www.", "", Yii::app()->request->serverName); // FOR TEST
    }

    public function getSe_n() {
        if ($se_m = file(Yii::getPathOfAlias('webroot.stats') . "/se.dat")) {
            for ($i = 0; $i < count($se_m); $i++)
                $se_m[$i] = iconv("CP1251", "UTF-8", $se_m[$i]);
            foreach ($se_m as $vl) {
                list($s1, $s2, $s3) = explode("|", $vl);
                $se_n[$s1] = rtrim($s3);
                $se_nn[$s1] = $s2;
            }
        }
        return array(
            'se_n' => $se_n,
            'se_nn' => $se_nn,
        );
    }

    public function initFull() {
        $s = $this->initRun();
        $this->_zp = $s['zp'];
    }

    public function initRun() {
        if ($robots = file(Yii::getPathOfAlias('webroot.stats') . "/robots.dat")) {
            $i = 0;
            for ($i = 0; $i < count($robots); $i++)
                $robots[$i] = iconv("CP1251", "UTF-8", $robots[$i]);
            foreach ($robots as $val) {
                list($rb1, $rb2) = explode("|", $val);
                $rb2 = trim($rb2);
                $this->rbd[$i++] = rtrim($rb1);
                if (!empty($rb2))
                    $rbdn[$rb2][] = rtrim($rb1);
                $robo[] = $rb2;
            }
        }

        if ($hosts = file(Yii::getPathOfAlias('webroot.stats') . "/hosts.dat")) {
            $i = 0;
            for ($i = 0; $i < count($hosts); $i++)
                $hosts[$i] = iconv("CP1251", "UTF-8", $hosts[$i]);
            foreach ($hosts as $val) {
                list($hb1, $hb2) = explode("|", $val);
                $hb2 = trim($hb2);
                $this->hbd[$i++] = rtrim($hb1);
                if (!empty($hb2))
                    $hbdn[$hb2][] = rtrim($hb1);
                $robo[] = $hb2;
            }
        }
        $this->robo = array_unique($robo);






        foreach ($this->rbd as $val) {
            $zp .= " LOWER(user) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
        }
        if (filesize(Yii::getPathOfAlias('webroot.stats') . "/hosts.dat")) {
            foreach ($this->hbd as $val) {
                $zp .= " LOWER(host) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
            }
        }
        $zp .= " LOWER(user) NOT LIKE '' AND";
        if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/skip.dat")) {
            if ($skip = file(Yii::getPathOfAlias('webroot.stats') . "/skip.dat")) {
                foreach ($skip as $vl) {
                    list($s1, $s2) = explode("|", $vl);
                    $zp2 .= " $s1 NOT LIKE '%" . rtrim($s2) . "%' AND";
                }
            }
        }

        $zp .= $zp2;
        $this->_zp = substr($zp, 0, -4);


        if ($se_m = file(Yii::getPathOfAlias('webroot.stats') . "/se.dat")) {
            for ($i = 0; $i < count($se_m); $i++)
                $se_m[$i] = iconv("CP1251", "UTF-8", $se_m[$i]);
            foreach ($se_m as $vl) {
                list($s1, $s2, $s3) = explode("|", $vl);
                $se_n[$s1] = rtrim($s3);
                $se_nn[$s1] = $s2;
            }
        }
        if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
            if ($fx_m = file(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
                $this->_zfx = "";
                $pf = "";
                for ($i = 0; $i < count($fx_m); $i++)
                    $fx_m[$i] = iconv("CP1251", "UTF-8", $fx_m[$i]);
                foreach ($fx_m as $vl) {
                    list($s1, $s2, $s3) = explode("|", $vl);
                    $this->_zfx .= $pf . "LOWER(" . $s1 . ") LIKE '%" . mb_strtolower($s2) . "%'";
                    $pf = " OR ";
                    $s3 = rtrim($s3);
                    if (!empty($s3))
                        $fxn[$s3][] = $s1 . "|" . $s2;
                    $fxo[] = $s3;
                }
            }
        }

        foreach ($se_nn as $val) {
            $this->_cse_m .= " OR LOWER(refer) LIKE '%$val%'";
            $this->_cot_m .= " AND LOWER(refer) NOT LIKE '%$val%'";
        }


        return array(
            'robo' => $this->robo,
            'rbd' => $this->rbd,
            'hbd' => $this->hbd,
            'zp' => $this->_zp,
            'cse_m' => $this->_cse_m,
            'cot_m' => $this->_cot_m,
            'se_n' => $se_n,
            'se_nn' => $se_nn,
            'site' => $this->getSite()
        );
    }

    public function getToday() {
        foreach (StatsMainHistory::model()->findAll() as $rw) {
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


        list($s_date, $f_date) = str_replace("+", "", array('13.03.2015', '15.03.2015'));

        $sdate = trim($s_date);
        $fdate = trim($f_date);
        /* if ($robots = file(Yii::getPathOfAlias('webroot.stats') . "/robots.dat")) {
          $i = 0;
          for ($i = 0; $i < count($robots); $i++)
          $robots[$i] = iconv("CP1251", "UTF-8", $robots[$i]);
          foreach ($robots as $val) {
          list($rb1, $rb2) = explode("|", $val);
          $rb2 = trim($rb2);
          $this->rbd[$i++] = rtrim($rb1);
          if (!empty($rb2))
          $rbdn[$rb2][] = rtrim($rb1);
          $robo[] = $rb2;
          }
          }
          if ($hosts = file(Yii::getPathOfAlias('webroot.stats') . "/hosts.dat")) {
          $i = 0;
          for ($i = 0; $i < count($hosts); $i++)
          $hosts[$i] = iconv("CP1251", "UTF-8", $hosts[$i]);
          foreach ($hosts as $val) {
          list($hb1, $hb2) = explode("|", $val);
          $hb2 = trim($hb2);
          $hbd[$i++] = rtrim($hb1);
          if (!empty($hb2))
          $hbdn[$hb2][] = rtrim($hb1);
          $robo[] = $hb2;
          }
          }
          $this->robo = array_unique($robo); */


        $iniRun = $this->initRun();
        $this->rbd = $iniRun['rbd'];
        $hbd = $iniRun['hbd'];
        foreach ($this->rbd as $val) {
            $zp .= " LOWER(user) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
        }
        if (filesize(Yii::getPathOfAlias('webroot.stats') . "/hosts.dat")) {
            foreach ($hbd as $val) {
                $zp .= " LOWER(host) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
            }
        }
        $zp .= " LOWER(user) NOT LIKE '' AND";
        if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/skip.dat")) {
            if ($skip = file(Yii::getPathOfAlias('webroot.stats') . "/skip.dat")) {
                foreach ($skip as $vl) {
                    list($s1, $s2) = explode("|", $vl);
                    $zp2 .= " $s1 NOT LIKE '%" . rtrim($s2) . "%' AND";
                }
            }
        }

        $zp .= $zp2;
        $this->_zp = substr($zp, 0, -4);

        if ($se_m = file(Yii::getPathOfAlias('webroot.stats') . "/se.dat")) {
            for ($i = 0; $i < count($se_m); $i++)
                $se_m[$i] = iconv("CP1251", "UTF-8", $se_m[$i]);
            foreach ($se_m as $vl) {
                list($s1, $s2, $s3) = explode("|", $vl);
                $se_n[$s1] = rtrim($s3);
                $se_nn[$s1] = $s2;
            }
        }
        if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
            if ($fx_m = file(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
                $this->_zfx = "";
                $pf = "";
                for ($i = 0; $i < count($fx_m); $i++)
                    $fx_m[$i] = iconv("CP1251", "UTF-8", $fx_m[$i]);
                foreach ($fx_m as $vl) {
                    list($s1, $s2, $s3) = explode("|", $vl);
                    $this->_zfx .= $pf . "LOWER(" . $s1 . ") LIKE '%" . mb_strtolower($s2) . "%'";
                    $pf = " OR ";
                    $s3 = rtrim($s3);
                    if (!empty($s3))
                        $fxn[$s3][] = $s1 . "|" . $s2;
                    $fxo[] = $s3;
                }
            }
        }

        foreach ($se_nn as $val) {
            $this->_cse_m .= " OR LOWER(refer) LIKE '%$val%'";
            $this->_cot_m .= " AND LOWER(refer) NOT LIKE '%$val%'";
        }

        $c = 0;
        $sdate = 0;
        $all_uniqs = 0;
        $all_hits = 0;
        $all_se = 0;
        $all_other = 0;
        $all_fix = 0;


        // $r = Yii::app()->db->createCommand();
        // $r->selectDistinct('day, dt');
        // $r->from('cms_surf');
        // $r->order('i');



        $i = 0;
        //$res1 = $r->queryRow();
        //  $fdate = $res1['dt'];

        $visits = $this->visits(date('Ymd'));
        $system = $this->visitSystem(date('Ymd'));


        return array(
            'hosts' => $visits['hosts'],
            'hits' => $visits['hits'],
            'search' => $system['search'],
            'sites' => $system['sites'],
        );
    }

    /**
     * @param date $date date("Ymd")
     * @return array Hits & hosts
     */
    public function visits($date) {
        $sql = Yii::app()->db;
        $sql->createCommand()->selectDistinct('day, dt');
        $sql->createCommand()->from("{$sql->tablePrefix}surf");
        if (isset($date)) {
            $sql->where('dt=:date', array(':date' => $date));
        }
        $sql->createCommand()->order('i');
        $result = $sql->createCommand()->queryAll();
        if (count($result)) {
            foreach ($sql->createCommand()->queryAll() as $dtm) {
                list($m_hosts[$dtm['dt']], $m_hits[$dtm['dt']]) = $this->countVisits($dtm['dt']);
            }
        } else {
            $m_hosts = array(0);
            $m_hits = array(0);
        }
        return array(
            'hosts' => $m_hosts,
            'hits' => $m_hits,
        );
    }

    /**
     * @param string $date Format date("Ymd")
     * @return array
     */
    public function visitSystem($date, $fdate = false, $rwz = false) {
               //$db = Yii::app()->db;
        if ($date != $fdate && isset($rwz["search"][$date])) {
            $m_se[$date] = $rwz["search"][$date];
            $m_other[$date] = $rwz["other"][$date];
            $m_fix[$date] = $rwz["fix"][$date];
        } else {
            $m_se[$date] = $this->countSearchEngine($date);
            $m_other[$date] = $this->countOther($date);

            if ($this->fx)
                $m_fix[$date] = $this->countFix($date);
            else
                $m_fix[$date] = 0;
            if (isset($rwz["dt"])) {
                if ($date != $fdate and !in_array($date, $rwz["dt"])) {
                    mysql_query("INSERT INTO {$this->db->tablePrefix}main_history(dt,hosts,hits,search,other,fix) VALUES('" . $date . "','" . $m_uniqs[$date] . "','" . $m_hits[$date] . "','" . $m_se[$date] . "','" . $m_other[$date] . "','" . $m_fix[$date] . "')");
                    mysql_query("DELETE me FROM {$this->db->tablePrefix}main_history as me, {$this->db->tablePrefix}main_history as clone WHERE me.dt = clone.dt AND me.i > clone.i");
                }
            }
        }
        return array(
            'search' => $m_se,
            'sites' => $m_other,
            'fix' => $m_fix,
        );
    }

    public $ingoreRoutes = array(
        'admin',
        'admin/*',
            // 'admin/stats/pagevisit'
    );

    private function checkIgnoreRoute() {
        if (in_array(Yii::app()->getRequest()->getPathInfo(), $this->ingoreRoutes)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Initialize stats component
     */
    public function record() {
        if (!Yii::app()->request->isAjaxRequest && !Yii::app()->controller->isAdminController) { // !$this->checkIgnoreRoute()



            $offset = 0;

            $t = time() + 3600 * $offset;
            $day = date("D", $t);
            $dt = date("Y-m-d", $t);
            $tm = date("H:i", $t);
            $refer = $_SERVER['HTTP_REFERER'];
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $user = $_SERVER['HTTP_USER_AGENT'];
            $req = $_SERVER['REQUEST_URI'];
            if ($ip = $_SERVER['HTTP_X_FORWARDED_FOR']) {
                if (!stristr($_SERVER['HTTP_X_FORWARDED_FOR'], CMS::getip()) and !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                    $ip .= ", " . CMS::getip();
                else
                    $ip = CMS::getip();
                $proxy = CMS::getip();
            }
            else {
                $ip = CMS::getip();
                $proxy = "";
            }
            if ($proxy == $ip)
                $proxy = "";
            $a = explode(", ", $ip);
            $real_ip = $a[count($a) - 1];
            if (!empty($proxy))
                $host = gethostbyaddr($proxy);
            else if ($host = gethostbyaddr($ip))
                ;
            else if ($host = gethostbyaddr($real_ip))
                ;
            else
                $host = $ip;


            $model = new StatsSurf();
            $model->day = $day;
            $model->dt = $dt;
            $model->tm = $tm;
            $model->refer = $refer;
            $model->ip = $ip;
            $model->proxy = $proxy;
            $model->host = $host;
            $model->lang = $lang;
            $model->user = $user;
            $model->req = $req;
            if (!$model->save(false, false, false)) {
                Yii::log('Error save stats', 'info', 'stats');
            }
        }
    }

    /**
     * @param type $date Format date("Ymd")
     * @param string $this->_zp Require
     * @param string $this->_cot_m Require
     * @return array
     */
    public function countOther($date) {

        if ($this->_cot_m || $this->_zp) {
            $sql = "SELECT COUNT(refer) FROM {{surf}} WHERE dt='" . $date . "' AND refer <> '' AND LOWER(refer) NOT REGEXP '^(ftp|http|https):\/\/(www.)*" . $this->site . "' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%query=%'" . $this->_cot_m . ") AND " . $this->_zp . "";
            //die($sql);
           //$sql="SELECT COUNT(refer) FROM cms_surf WHERE dt='20150315' AND refer <> '' AND LOWER(refer) NOT REGEXP '^(ftp|http|https):\/\/(www.)*obuvayka.com' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%query=%' AND LOWER(refer) NOT LIKE '%webalta.ru%' AND LOWER(refer) NOT LIKE '%icq.com%' AND LOWER(refer) NOT LIKE '%meta.ua%' AND LOWER(refer) NOT LIKE '%all.by%' AND LOWER(refer) NOT LIKE '%nigma.ru%') AND LOWER(user) NOT LIKE '%yandex%' AND LOWER(user) NOT LIKE '%stackrambler%' AND LOWER(user) NOT LIKE '%mail.ru%' AND LOWER(user) NOT LIKE '%google%' AND LOWER(user) NOT LIKE '%msnbot%' AND LOWER(user) NOT LIKE '%bing%' AND LOWER(user) NOT LIKE '%slurp%' AND LOWER(user) NOT LIKE '%add%' AND LOWER(user) NOT LIKE '%crawler%' AND LOWER(user) NOT LIKE '%search%' AND LOWER(user) NOT LIKE '%spider%' AND LOWER(user) NOT LIKE '%libwww-perl%' AND LOWER(user) NOT LIKE '%wget%' AND LOWER(user) NOT LIKE '%java%' AND LOWER(user) NOT LIKE '%bot%' AND LOWER(user) NOT LIKE '%scanner%' AND LOWER(user) NOT LIKE '%ia_archiver%' AND LOWER(user) NOT LIKE '%checker%' AND LOWER(user) NOT LIKE '%link%' AND LOWER(user) NOT LIKE '%php%' AND LOWER(user) NOT LIKE '%rss%' AND LOWER(user) NOT LIKE '%url%' AND LOWER(user) NOT LIKE '%project%' AND LOWER(user) NOT LIKE '%xml%' AND LOWER(user) NOT LIKE '%lwp%' AND LOWER(user) NOT LIKE '%refer%' AND LOWER(user) NOT LIKE '%validator%' AND LOWER(user) NOT LIKE '%porn%' AND LOWER(user) NOT LIKE '%tnx%' AND LOWER(user) NOT LIKE '%xap spider%' AND LOWER(user) NOT LIKE '%www%' AND LOWER(user) NOT LIKE '%site%' AND LOWER(user) NOT LIKE '%http%' AND LOWER(host) NOT LIKE '%msnbot%' AND LOWER(host) NOT LIKE '%asrv130.qwarta.ru%' AND LOWER(host) NOT LIKE '%asrv145.qwarta.ru%' AND LOWER(host) NOT LIKE '%193.232.121.%' AND LOWER(host) NOT LIKE '%94.77.64.%' AND LOWER(user) NOT LIKE ''";
          //  $sql="SELECT COUNT(refer) FROM cms_surf WHERE dt='20150315' AND refer <> '' AND LOWER(refer) NOT REGEXP '^(ftp|http|https):\/\/(www.)*obuvayka.com' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%query=%' AND LOWER(refer) NOT LIKE '%webalta.ru%' AND LOWER(refer) NOT LIKE '%icq.com%' AND LOWER(refer) NOT LIKE '%meta.ua%' AND LOWER(refer) NOT LIKE '%all.by%' AND LOWER(refer) NOT LIKE '%nigma.ru%') AND LOWER(user) NOT LIKE '%yandex%' AND LOWER(user) NOT LIKE '%stackrambler%' AND LOWER(user) NOT LIKE '%mail.ru%' AND LOWER(user) NOT LIKE '%google%' AND LOWER(user) NOT LIKE '%msnbot%' AND LOWER(user) NOT LIKE '%bing%' AND LOWER(user) NOT LIKE '%slurp%' AND LOWER(user) NOT LIKE '%add%' AND LOWER(user) NOT LIKE '%crawler%' AND LOWER(user) NOT LIKE '%search%' AND LOWER(user) NOT LIKE '%spider%' AND LOWER(user) NOT LIKE '%libwww-perl%' AND LOWER(user) NOT LIKE '%wget%' AND LOWER(user) NOT LIKE '%java%' AND LOWER(user) NOT LIKE '%bot%' AND LOWER(user) NOT LIKE '%scanner%' AND LOWER(user) NOT LIKE '%ia_archiver%' AND LOWER(user) NOT LIKE '%checker%' AND LOWER(user) NOT LIKE '%link%' AND LOWER(user) NOT LIKE '%php%' AND LOWER(user) NOT LIKE '%rss%' AND LOWER(user) NOT LIKE '%url%' AND LOWER(user) NOT LIKE '%project%' AND LOWER(user) NOT LIKE '%xml%' AND LOWER(user) NOT LIKE '%lwp%' AND LOWER(user) NOT LIKE '%refer%' AND LOWER(user) NOT LIKE '%validator%' AND LOWER(user) NOT LIKE '%porn%' AND LOWER(user) NOT LIKE '%tnx%' AND LOWER(user) NOT LIKE '%xap spider%' AND LOWER(user) NOT LIKE '%www%' AND LOWER(user) NOT LIKE '%site%' AND LOWER(user) NOT LIKE '%http%' AND LOWER(host) NOT LIKE '%msnbot%' AND LOWER(host) NOT LIKE '%asrv130.qwarta.ru%' AND LOWER(host) NOT LIKE '%asrv145.qwarta.ru%' AND LOWER(host) NOT LIKE '%193.232.121.%' AND LOWER(host) NOT LIKE '%94.77.64.%' AND LOWER(user) NOT LIKE ''";
            $command = Yii::app()->db->createCommand($sql);
            $res = $command->queryRow(false);

            return $res[0];
        } else {
            throw new CException(Yii::t('yii', 'Error in {fn} not found param "_cot_m" or "_zp"', array('{fn}' => __FUNCTION__)));
        }
    }

    /**
     * @param type $date Format date("Ymd")
     * @param string $this->_zfx Require
     * @return array
     */
    public function countFix($date) {
        if (isset($this->_zfx)) {
            $sql = "SELECT COUNT(i) FROM {{surf}} WHERE (" . $this->_zfx . ") AND dt='" . $date . "' AND " . $this->_zp . "";
            $command = Yii::app()->db->createCommand($sql);
            $res = $command->queryRow(false);
            return $res[0];
        } else {
            throw new CException(Yii::t('yii', 'Error in {fn} not found param "_zfx"', array('{fn}' => __FUNCTION__)));
        }
    }

    /**
     * @param type $date Format date("Ymd")
     * @param string $this->_zfx Require
     * @param string $this->_cse_m Require
     * @return array
     */
    public function countSearchEngine($date) {
        if (isset($this->_cse_m) || isset($this->_zp)) {
            $sql = "SELECT COUNT(refer) FROM {{surf}} WHERE dt='" . $date . "' AND (LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%'" . $this->_cse_m . ") AND LOWER(refer) NOT LIKE '%@%' AND " . $this->_zp . "";
            $command = Yii::app()->db->createCommand($sql);
            $res = $command->queryRow(false);
            return $res[0];
        } else {
            throw new CException(Yii::t('yii', 'Error in {fn} not found param "_cse_m" or "_zp"', array('{fn}' => __FUNCTION__)));
        }
    }

    /**
     * @param type $date Format date("Ymd")
     * @param string $this->_zp Require
     * @return array
     */
    public function countVisits($date) {

        $s = $this->initRun();
        $zp = $s['zp'];
        //  die('dasdas');
        if ($zp) {
            $sql = "SELECT COUNT(DISTINCT ip), COUNT(i) FROM {{surf}} WHERE dt='" . $date . "' AND " . $zp;
            $command = Yii::app()->db->createCommand($sql);
            return $command->queryRow(false);
        } else {
            throw new CException(Yii::t('yii', 'Error in {fn} not found param "_zp"', array('{fn}' => __FUNCTION__)));
        }
    }

}
