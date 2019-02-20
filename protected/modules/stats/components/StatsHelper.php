<?php

class StatsHelper {

    public static function getRowUserAgent($user_agent, $refer) {
        Yii::import('app.addons.Browser');
        $browser = new Browser();
        $browser->setUserAgent($user_agent);
        $content = '';
        if (!self::is_robot($user_agent, $refer)) {
            $brw = self::getBrowser($user_agent);
            if ($brw != "")
                $content .= Html::image(Yii::app()->getModule('stats')->assetsUrl . '/images/browsers/' . $brw, $user_agent, array(
                            //'title' => $user_agent,
                            'title' => Yii::t('StatsModule.default', 'BROWSER', array(
                                '{name}' => $browser->getBrowser(), //.' '.$user_agent,
                                '{v}' => $browser->getVersion()
                            )),
                            'class' => 'img-thumbnail',
                            'data-toggle' => "tooltip",
                            'data-placement' => "top",
                ));
        }
        $content .= self::getPlatformByImage($user_agent);
        $content .= self::getMobileByImage($user_agent);

        //$content.= $user_agent;
        return $content;
    }

    public static function getMobileByImage($user_agent) {
        Yii::import('app.addons.Browser');
        $browser = new Browser();
        $browser->setUserAgent($user_agent);
        // return $browser->getPlatform();

        if ($browser->isMobile()) {
            $img = 'mobile.png';
            $name = 'Мобильное устройство';
        } elseif ($browser->isTablet()) {
            $name = 'Планшет';
            $img = 'tablet.png';
        } else {

            $name = $browser->getPlatform();
            $img = self::getPlatformByImage($user_agent, false);
        }
        return Html::image(Yii::app()->getModule('stats')->assetsUrl . '/images/platform/' . $img, $name, array(
                    'data-toggle' => "tooltip",
                    'data-placement' => "top",
                    'class' => 'img-thumbnail',
                    'title' => $name
        ));
    }

    public static function getPlatformByImage($user_agent, $render = true) {
        Yii::import('app.addons.Browser');
        $browser = new Browser();
        $browser->setUserAgent($user_agent);
        // return $browser->getPlatform();
        if ($browser->isRobot()) {
            return Html::image(Yii::app()->getModule('stats')->assetsUrl . '/images/platform/robot.png', $browser->getPlatform(), array(
                        'data-toggle' => "tooltip",
                        'data-placement' => "top",
                        'title' => 'Робот',
                        'class' => 'img-thumbnail',
            ));
        }
        switch ($browser->getPlatform()) {
            case Browser::PLATFORM_ANDROID: $img = "android.png";
                break;
            case Browser::PLATFORM_WINDOWS: $img = "windows.png";
                break;
            case Browser::PLATFORM_WINDOWS_7: $img = "windows.png";
                break;
            case Browser::PLATFORM_WINDOWS_8: $img = "windows_8.png";
                break;
            case Browser::PLATFORM_WINDOWS_10: $img = "windows_8.png";
                break;
            case Browser::PLATFORM_FREEBSD: $img = "freebsd.png";
                break;
            case Browser::PLATFORM_LINUX: $img = "linux.png";
                break;
            case Browser::PLATFORM_IPHONE: $img = "apple.png";
                break;
            case Browser::PLATFORM_APPLE: $img = "apple.png";
                break;
            case Browser::PLATFORM_IPAD: $img = "apple.png";
                break;
            default :
                  return $browser->getPlatform();
                break;
        }
        if ($render) {
      
            return Html::image(Yii::app()->getModule('stats')->assetsUrl . '/images/platform/' . $img, $browser->getPlatform(), array(
                        'data-toggle' => "tooltip",
                        'data-placement' => "top",
                        'class' => 'platform',
                        'class' => 'img-thumbnail',
                        'title' => Yii::t('StatsModule.default', 'PLATFORM', array('{name}' => $browser->getPlatform()))
            ));

        } else {
            return $img;
        }
    }

    public static function linkDetail($link) {
        return Html::link(Yii::t('StatsModule.default', 'DETAIL'), $link, array('target' => '_blank', 'class' => 'btn btn-xs btn-info'));
    }

    public static function getRowHost($ip, $proxy, $host, $lang) {
        $content = '';
        $p = ($lang)? self::$LANG[mb_strtoupper($lang)]:'';
        if ($ip == "") {
            $content .= '<span class="text-muted">неизвестно</span>';
        } else {
            $content .= "<a target=_blank href=\"http://www.tcpiputils.com/browse/ip-address/" . (($ip != "") ? $ip : $host) . "\">" . $host . "</a>";
        }
        if ($host != "") {
            $content .= "<br>Язык: " . (!empty($p) ? $p : "<font color=grey>неизвестно</font>");
            if (file_exists(Yii::getPathOfAlias('webroot.uploads.language') . DS . mb_strtolower($lang) . ".png")) {
                $content .= Html::image('/uploads/language/' . mb_strtolower($lang) . '.png', $p);
            }
        }
        return $content;
    }

    public function browserName($brw) {
        switch ($brw) {
            case "ie.png": $name = "MS Internet Explorer";
                break;
            case "opera.png": $name = "Opera";
                break;
            case "firefox.png": $name = "Firefox";
                break;
            case "chrome.png": $name = "Google Chrome";
                break;
            case "mozilla.png": $name = "Mozilla";
                break;
            case "safari.png": $name = "Apple Safari";
                break;
            case "mac.png": $name = "Macintosh";
                break;
            case "maxthon.png": $name = "Maxthon (MyIE)";
                break;
            default: $name = "другие";
                break;
        }
        if (!empty($brw)) {
            $img = Html::image(Yii::app()->getModule('stats')->assetsUrl . '/images/browsers/' . $brw, $name);
        } else {
            $img = '';
        }

        $linkRoute = ['/admin/stats/browsers/detail'];
        if (isset($this->sdate)) {
            $linkRoute['s_date'] = $this->sdate;
        }

        if (isset($this->f_date)) {
            $linkRoute['s_date'] = $this->fdate;
        }
        if (isset($brw)) {
            $linkRoute['qs'] = $brw;
        }
        // if(isset($brw)){
        $linkRoute['sort'] = (empty($this->sort) ? "ho" : $this->sort);
        //}



        $content = Html::link($img . ' ' . $name, $linkRoute);

        // $content = Html::link($img . ' ' . $name, "/admin/stats/browsers/detail?s_date=" . $this->sdate . "&f_date=" . $this->fdate . "&qs=" . $brw . "&sort=" . (empty($this->sort) ? "ho" : $this->sort), array('target' => '_blank'));
        return $content;
    }

    public static function getRowIp($refer, $ip) {

        if ($refer != "unknown") {
            //return CMS::ip($refer,1);
        }
        if ($ip != "")
            return "<br><a target=_blank href=\"?item=ip&qs=" . $ip . "\">через proxy</a>";

        $content = '';
        if ($refer != "unknown")
            $content .= "<a target=_blank href=\"?item=ip&qs=" . $refer . "\">" . $refer . "</a>";
        else
            $content .= '<span class="text-muted">неизвестно</span>';
        if ($ip != "")
            $content .= "<br><a target=_blank href=\"?item=ip&qs=" . $ip . "\">через proxy</a>";

        return $content;
    }

    public static function checkSearchEngine($refer, $engine, $query) {
        $content = '';
        if ($engine == "G" and ! empty($query) and stristr($refer, "/url?"))
            $refer = str_replace("/url?", "/search?", $refer);
        $content .= Yii::app()->controller->echo_se($engine);
        if (empty($query))
            $query = '<span class="text-muted">неизвестно</span>';
        $content .= ": <a target=_blank href=\"" . $refer . "\">" . $query . "</a>";
        return $content;
    }

    public static function renderReferer($ref) {
        $text = '';
        $refer = self::Ref($ref);
        if (is_array($refer)) {
            list($engine, $query) = $refer;
            if ($engine == "G" and ! empty($query) and stristr($ref, "/url?"))
                $ref = str_replace("/url?", "/search?", $ref);
            $text .= Yii::app()->controller->echo_se($engine);
            if (empty($query))
                $query = '<span class="text-muted">неизвестно</span>';
            $text .= ': <a target="_blank" href="' . $ref . '">' . $query . '</a>';
        } else if ($refer == "")
            $text .= '<span class="text-muted">неизвестно</span>';
        else {
            $text .= '<a target="_blank" href="' . $ref . '">';
            if (stristr(urldecode($ref), "xn--")) {
                $IDN = new idna_convert(array('idn_version' => 2008));
                $text .= $IDN->decode(urldecode($ref));
            } else
                $text .= urldecode($ref);
            $text .= "</a>";
        }
        return $text;
    }

    public static function timeLink($array, $key) {
        $tu = "";
        foreach ($array[$key] as $rw) {
            $tu .= $rw[0] . " <a target=_blank href=" . $rw[1] . ">" . CMS::truncate($rw[1], 20) . "</a><br>";
        }
        return $tu;
    }

    public static function checkIdna($ref) {

        $content = '';
        if ($ref == "")
            $content .= "<font color=grey>неизвестно</font>";
        else {
            $content .= "<a target=_blank href=\"" . $ref . "\">";
            if (stristr(urldecode($ref), "xn--")) {
                $IDN = new idna_convert(array('idn_version' => 2008));
                $content .= $IDN->decode(urldecode($ref));
            } else
                $content .= urldecode($ref);
            $content .= "</a>";
        }

        return $content;
    }

    public static $MONTH = array(
        "12" => "Декабрь",
        "11" => "Ноябрь",
        "10" => "Октябрь",
        "09" => "Сентябрь",
        "08" => "Август",
        "07" => "Июль",
        "06" => "Июнь",
        "05" => "Май",
        "04" => "Апрель",
        "03" => "Март",
        "02" => "Февраль",
        "01" => "Январь"
    );
    public static $DAY = array(
        "Mon" => "ПН: ",
        "Tue" => "ВТ: ",
        "Wed" => "СР: ",
        "Thu" => "ЧТ: ",
        "Fri" => "ПТ: ",
        "Sat" => "<font color='#de3163'>СБ:</font> ",
        "Sun" => "<font color='#de3163'>ВС:</font> "
    );
    public static $LANG = array(// ISO 639
        "AA" => "Afar",
        "AB" => "Abkhazian",
        "AE" => "Avestan",
        "AF" => "Afrikaans",
        "AK" => "Akan",
        "AM" => "Amharic",
        "AN" => "Aragonese",
        "AR" => "Arabic",
        "AS" => "Assamese",
        "AV" => "Avaric",
        "AY" => "Aymara",
        "AZ" => "Azerbaijani",
        "BA" => "Bashkir",
        "BE" => "Byelorussian",
        "BG" => "Bulgarian",
        "BH" => "Bihari",
        "BI" => "Bislama",
        "BM" => "Bambara",
        "BN" => "Bengali",
        "BO" => "Tibetan",
        "BR" => "Breton",
        "BS" => "Bosnian",
        "CA" => "Catalan",
        "CE" => "Chechen",
        "CH" => "Chamorro",
        "CO" => "Corsican",
        "CR" => "Cree",
        "CS" => "Czech",
        "CU" => "Old Church Slavonic",
        "CV" => "Chuvash",
        "CY" => "Welsh",
        "DA" => "Danish",
        "DE" => "German",
        "DV" => "Divehi",
        "DZ" => "Bhutani",
        "EE" => "Ewe",
        "EL" => "Greek",
        "EN" => "English",
        "EO" => "Esperanto",
        "ES" => "Spanish",
        "ET" => "Estonian",
        "EU" => "Basque",
        "FA" => "Persian",
        "FF" => "Fula",
        "FI" => "Finnish",
        "FJ" => "Fiji",
        "FO" => "Faeroese",
        "FR" => "French",
        "FY" => "Frisian",
        "GA" => "Irish",
        "GD" => "Gaelic",
        "GL" => "Galician",
        "GN" => "Guarani",
        "GU" => "Gujarati",
        "GV" => "Manx",
        "HA" => "Hausa",
        "HE" => "Hebrew",
        "HI" => "Hindi",
        "HO" => "Hiri Motu",
        "HR" => "Croatian",
        "HT" => "Haitian",
        "HU" => "Hungarian",
        "HY" => "Armenian",
        "HZ" => "Herero",
        "IA" => "Interlingua",
        "ID" => "Indonesian",
        "IE" => "Interlingue",
        "IG" => "Igbo",
        "II" => "Nuosu",
        "IK" => "Inupiak",
        "IN" => "Indonesian",
        "IO" => "Ido",
        "IS" => "Icelandic",
        "IT" => "Italian",
        "IU" => "Inuktitut",
        "IW" => "Hebrew",
        "JA" => "Japanese",
        "JV" => "Javanese",
        "JI" => "Yiddish",
        "JW" => "Javanese",
        "KA" => "Georgian",
        "KG" => "Kongo",
        "KI" => "Kikuyu",
        "KJ" => "Kwanyama",
        "KK" => "Kazakh",
        "KZ" => "Kazakh",
        "KL" => "Greenlandic",
        "KM" => "Cambodian",
        "KN" => "Kannada",
        "KO" => "Korean",
        "KR" => "Kanuri",
        "KS" => "Kashmiri",
        "KU" => "Kurdish",
        "KV" => "Komi",
        "KW" => "Cornish",
        "KY" => "Kirghiz",
        "LA" => "Latin",
        "LB" => "Luxembourgish",
        "LG" => "Ganda",
        "LI" => "Limburgish",
        "LN" => "Lingala",
        "LO" => "Laothian",
        "LT" => "Lithuanian",
        "LU" => "Luba-Katanga",
        "LV" => "Latvian",
        "MG" => "Malagasy",
        "MH" => "Marshallese",
        "MI" => "Maori",
        "MK" => "Macedonian",
        "ML" => "Malayalam",
        "MN" => "Mongolian",
        "MO" => "Moldavian",
        "MR" => "Marathi",
        "MS" => "Malay",
        "MT" => "Maltese",
        "MY" => "Burmese",
        "NA" => "Nauru",
        "NB" => "Norwegian Bokmal",
        "ND" => "North Ndebele",
        "NE" => "Nepali",
        "NG" => "Ndonga",
        "NL" => "Dutch",
        "NN" => "Norwegian Nynorsk",
        "NO" => "Norwegian",
        "NR" => "South Ndebele",
        "NV" => "Navajo",
        "NY" => "Chichewa",
        "OC" => "Occitan",
        "OJ" => "Ojibwe",
        "OM" => "Oromo",
        "OR" => "Oriya",
        "OS" => "Ossetian",
        "PA" => "Punjabi",
        "PI" => "Pali",
        "PL" => "Polish",
        "PS" => "Pashto",
        "PT" => "Portuguese",
        "QU" => "Quechua",
        "RM" => "Rhaeto-Romance",
        "RN" => "Kirundi",
        "RO" => "Romanian",
        "RU" => "Russian",
        "RW" => "Kinyarwanda",
        "SA" => "Sanskrit",
        "SC" => "Sardinian",
        "SD" => "Sindhi",
        "SE" => "Northern Sami",
        "SG" => "Sangro",
        "SH" => "Serbo-Croatian",
        "SI" => "Singhalese",
        "SK" => "Slovak",
        "SL" => "Slovenian",
        "SM" => "Samoan",
        "SN" => "Shona",
        "SO" => "Somali",
        "SQ" => "Albanian",
        "SR" => "Serbian",
        "SS" => "Siswati",
        "ST" => "Sesotho",
        "SU" => "Sudanese",
        "SV" => "Swedish",
        "SW" => "Swahili",
        "TA" => "Tamil",
        "TE" => "Tegulu",
        "TG" => "Tajik",
        "TH" => "Thai",
        "TI" => "Tigrinya",
        "TK" => "Turkmen",
        "TL" => "Tagalog",
        "TN" => "Setswana",
        "TO" => "Tonga",
        "TR" => "Turkish",
        "TS" => "Tsonga",
        "TT" => "Tatar",
        "TW" => "Twi",
        "TY" => "Tahitian",
        "UG" => "Uighur",
        "UK" => "Ukrainian",
        "UR" => "Urdu",
        "UZ" => "Uzbek",
        "VE" => "Venda",
        "VI" => "Vietnamese",
        "VO" => "Volapuk",
        "WA" => "Walloon",
        "WO" => "Wolof",
        "XH" => "Xhosa",
        "YI" => "Yiddish",
        "YO" => "Yoruba",
        "ZA" => "Zhuang",
        "ZH" => "Chinese",
        "ZU" => "Zulu"
    );

    public static function getBrowser($UA) {
        if (stristr($UA, "Maxthon") or stristr($UA, "Myie"))
            return "maxthon.png";
        if (stristr($UA, "Opera") or stristr($UA, "OPR/"))
            return "opera.png";
        if (stristr($UA, "MSIE") or stristr($UA, "Trident"))
            return "ie.png";
        if (stristr($UA, "Firefox"))
            return "firefox.png";
        if (stristr($UA, "Chrome") or stristr($UA, "Android"))
        //if (stristr($UA, "Chrome"))
            return "chrome.png";
        if (stristr($UA, "Safari"))
            return "safari.png";
        if (stristr($UA, "Mac"))
            return "mac.png";
        if (stristr($UA, "Mozilla"))
            return "mozilla.png";
        else
            return "";
    }

    public static function get_encoding($str) {
        $cp_list = array('utf-8', 'cp1251');
        foreach ($cp_list as $k => $codepage) {
            if (md5($str) === md5(iconv($codepage, $codepage, $str))) {
                return $codepage;
            }
        }
        return null;
    }

    public static function se_google($ref) {
        $sw = "q=";
        $sw2 = "as_q=";
        $engine = "G";
        $url = urldecode($ref);
        $url = str_replace("aq=", "bb=", $url);
        if (stristr($url, "e=KOI8-R"))
            $url = convert_cyr_string($url, "k", "w");
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        if (stristr($match1[1], "%"))
            $match1[1] = urldecode($match1[1]);
        $match1[1] = trim($match1[1]);
        preg_match("/[?&]+" . $sw2 . "([^&]*)/i", $url . "&", $match2);
        $match2[1] = trim($match2[1]);
        if ($match2[1] == $match1[1])
            return array($engine, $match1[1]);
        if (!empty($match2[1]))
            return array($engine, ($match2[1] . " + " . $match1[1]));
        else
            return array($engine, $match1[1]);
    }

    public static function GetBrw($brw) {
        switch ($brw) {
            case "maxthon.png": return "AND (LOWER(user) LIKE '%maxthon%' OR LOWER(user) LIKE '%myie%')";
                break;
            case "opera.png": return "AND (LOWER(user) LIKE '%opera%' OR LOWER(user) LIKE '%opr/%')";
                break;
            case "ie.png": return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%opera%' AND (LOWER(user) LIKE '%msie%' OR LOWER(user) LIKE '%trident%')";
                break;
            case "firefox.png": return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%msie%' AND LOWER(user) NOT LIKE '%opera%' AND LOWER(user) LIKE '%firefox%'";
                break;
            case "chrome.png": return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%msie%' AND LOWER(user) NOT LIKE '%opera%' AND LOWER(user) NOT LIKE '%opr/%' AND LOWER(user) NOT LIKE '%firefox%' AND (LOWER(user) LIKE '%chrome%' OR LOWER(user) LIKE '%android%')";
                break;
            case "safari.png": return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%msie%' AND LOWER(user) NOT LIKE '%opera%' AND LOWER(user) NOT LIKE '%firefox%' AND LOWER(user) NOT LIKE '%chrome%' AND LOWER(user) NOT LIKE '%android%' AND LOWER(user) LIKE '%safari%'";
                break;
            case "mac.png": return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%msie%' AND LOWER(user) NOT LIKE '%opera%' AND LOWER(user) NOT LIKE '%firefox%' AND LOWER(user) NOT LIKE '%chrome%' AND LOWER(user) NOT LIKE '%safari%' AND LOWER(user) LIKE '%mac%'";
                break;
            case "mozilla.png": return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%msie%' AND LOWER(user) NOT LIKE '%opera%' AND LOWER(user) NOT LIKE '%firefox%' AND LOWER(user) NOT LIKE '%chrome%' AND LOWER(user) NOT LIKE '%safari%' AND LOWER(user) NOT LIKE '%trident%' AND LOWER(user) NOT LIKE '%mac%' AND LOWER(user) LIKE '%mozilla%'";
                break;
            default: return "AND LOWER(user) NOT LIKE '%maxthon%' AND LOWER(user) NOT LIKE '%myie%' AND LOWER(user) NOT LIKE '%msie%' AND LOWER(user) NOT LIKE '%trident%' AND LOWER(user) NOT LIKE '%opera%' AND LOWER(user) NOT LIKE '%opr/%' AND LOWER(user) NOT LIKE '%android%' AND LOWER(user) NOT LIKE '%firefox%' AND LOWER(user) NOT LIKE '%chrome%' AND LOWER(user) NOT LIKE '%safari%' AND LOWER(user) NOT LIKE '%mac%' AND LOWER(user) NOT LIKE '%mozilla%'";
                break;
        }
    }

    public static function echo_se($engine) {
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
                foreach ($se_n as $key => $val)
                    if (stristr(strip_tags($key), strip_tags($engine))) {
                        return "<b>" . $key . "</b>";
                        break;
                    }
                break;
        }
    }

    public static function se_mail1($ref) {
        $sw = "words=";
        $engine = "M";
        $url = urldecode($ref);
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        $match1[1] = trim($match1[1]);
        return array($engine, $match1[1]);
    }

    public static function se_mail2($ref) {
        $sw = "q=";
        $sw2 = "as_q=";
        $engine = "M";
        $url = urldecode($ref);
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        $match1[1] = trim($match1[1]);
        preg_match("/[?&]+" . $sw2 . "([^&]*)/i", $url . "&", $match2);
        $match2[1] = trim($match2[1]);
        if ($match2[1] == $match1[1])
            return array($engine, $match1[1]);
        if (!empty($match2[1]))
            return array($engine, ($match2[1] . " + " . $match1[1]));
        else
            return array($engine, $match1[1]);
    }

    public static function se_rambler($ref) {
        $sw = "words=";
        $sw1 = "query=";
        $sw2 = "old_q=";
        $engine = "R";
        $url = urldecode($ref);
        if (stristr($url, "btnG=оБКФЙ!"))
            $url = convert_cyr_string($url, "k", "w");
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        if (empty($match1))
            preg_match("/[?&]+" . $sw1 . "([^&]*)/iu", $url . "&", $match1);
        $match1[1] = trim($match1[1]);
        if (stristr($url, "infound=1")) {
            preg_match("/[?&]+" . $sw2 . "([^&]*)/i", $url . "&", $match2);
            return array($engine, ($match2[1] . " + " . $match1[1]));
        } else
            return array($engine, $match1[1]);
    }

    public static function se_yahoo($ref) {
        $sw = "p=";
        $engine = "H";
        $url = urldecode($ref);
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        $match1[1] = trim($match1[1]);
        return array($engine, $match1[1]);
    }

    public static function se_msn($ref) {
        $sw = "q=";
        $engine = "S";
        $url = urldecode($ref);
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        $match1[1] = trim($match1[1]);
        return array($engine, $match1[1]);
    }

    public static function is_robot($check, $check2) {
        $app = Yii::app()->stats;
        $rbd = $app->rbd;
        $hbd = $app->hbd;

        if (empty($check))
            return TRUE;
        if (isset($rbd))
            foreach ($rbd as $val)
                if (stristr($check, $val))
                    return TRUE;
        if (isset($hbd))
            foreach ($hbd as $val)
                if (stristr($check2, $val))
                    return TRUE;
        return FALSE;
    }

    public static function se_other($ref, $sw) {
        $engine = "?";
        $url = urldecode($ref);
        $url = stripslashes($url);
        $url = strip_tags($url);
        if (self::get_encoding($url) == "cp1251")
            $url = iconv("CP1251", "UTF-8", $url);
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        $match1[1] = trim($match1[1]);
        return array($engine, $match1[1]);
    }

    public static function se_sp($ref) {
        $app = Yii::app()->stats->se_n;
        foreach ($app['se_n'] as $key => $val) {
            if (stristr($ref, $se_nn[$key])) {
                $engine = $key;
                $sw = $val;
                $url = urldecode($ref);
                $url = stripslashes($url);
                $url = strip_tags($url);
                if (self::get_encoding($url) == "cp1251")
                    $url = iconv("CP1251", "UTF-8", $url);
                preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
                $match1[1] = trim($match1[1]);
                return array($engine, $match1[1]);
            }
        }
        return -1;
    }

    public static function utf8RawUrlDecode($source) {
        $decodedStr = '';
        $pos = 0;
        $len = strlen($source);
        while ($pos < $len) {
            $charAt = substr($source, $pos, 1);
            if ($charAt == '%') {
                $pos++;
                $charAt = substr($source, $pos, 1);
                if ($charAt == 'u') {
                    $pos++;
                    $unicodeHexVal = substr($source, $pos, 4);
                    $unicode = hexdec($unicodeHexVal);
                    $entity = "&#" . $unicode . ';';
                    $decodedStr .= utf8_encode($entity);
                    $pos += 4;
                } else {
                    $hexVal = substr($source, $pos, 2);
                    $decodedStr .= chr(hexdec($hexVal));
                    $pos += 2;
                }
            } else {
                $decodedStr .= $charAt;
                $pos++;
            }
        }
        return $decodedStr;
    }

    public static function se_yandex($ref) {
        $sw = "text=";
        $sw2 = "holdreq=";
        $engine = "Y";
        $rw = 0;
        if (stristr($ref, "yandpage")) {
            if (stristr($ref, "text%3D%25u")) {
                $rw = 1;
                if (stristr($ref, "holdreq%3D%25u"))
                    $rw = 2;
            }
        }
        $url = urldecode($ref);
        if (stristr($url, "Є") or stristr($url, "є") or stristr($url, "Ў") or stristr($url, "ў")) {
            $url = iconv("UTF-8", "CP866", $url);
            $url = iconv("CP1251", "UTF-8", $url);
        } else {
            if (substr_count(iconv("CP1251", "UTF-8", $url), "Р") > 2)
                ;
            else
                $url = iconv("CP1251", "UTF-8", $url);
            $url = stripslashes($url);
            $url = strip_tags($url);
            if (substr_count($url, "Г") > 2) {
                $url = iconv("UTF-8", "CP1251", $url);
                $url = iconv("UTF-8", "CP1252", $url);
                $url = iconv("CP1251", "UTF-8", $url);
            }
            if (substr_count($url, "Р") > 2)
                $url = iconv("UTF-8", "CP1251", $url);
            if (stristr($url, "°") or stristr($url, "Ѓ") or stristr($url, "„") or stristr($url, "‡") or stristr($url, "Ќ"))
                $url = iconv("UTF-8", "CP1251", $url);
        }
        preg_match("/[?&]+" . $sw . "([^&]*)/i", $url . "&", $match1);
        preg_match("/[?&]+" . $sw2 . "([^&]*)/i", $url . "&", $match2);
        if ($match2[1] == $match1[1])
            return array($engine, $match1[1]);
        if (!empty($match2[1]))
            return array($engine, ($match2[1] . " + " . $match1[1]));
        else
            return array($engine, $match1[1]);
    }

    public static function Ref($ref) {
        $site = Yii::app()->stats->getSite();
        if (($ref != "") and ! (stristr($ref, "://" . $site) and stripos($ref, "://" . $site, 6) == 0) and ! (stristr($ref, "://www." . $site) and stripos($ref, "://www." . $site, 6) == 0)) {

            $reff = str_replace("www.", "", $ref);
            if (!stristr($ref, "://")) {
                $reff = "://" . $reff;
                $ref = "://" . $ref;
            }
            if (stristr($reff, "://yandex") or stristr($reff, "://search.yaca.yandex") or stristr($reff, "://images.yandex"))
                return self::se_yandex($ref);
            else
            if (stristr($reff, "://google"))
                return self::se_google($ref);
            else
            if (stristr($reff, "://rambler") or stristr($reff, "://nova.rambler") or stristr($reff, "://search.rambler") or stristr($reff, "://ie4.rambler") or stristr($reff, "://ie5.rambler"))
                return self::se_rambler($ref);
            else
            if (stristr($reff, "://go.mail.ru") and stristr($reff, "words="))
                return self::se_mail1($ref);
            else
            if (stristr($reff, "://go.mail.ru") or stristr($reff, "://wap.go.mail.ru"))
                return self::se_mail2($ref);
            else
            if (stristr($reff, "://search.msn") or stristr($reff, "://search.live.com") or stristr($reff, "://ie.search.msn") or stristr($reff, "://bing"))
                return self::se_msn($ref);
            else
            if (stristr($reff, "://search.yahoo"))
                return self::se_yahoo($ref);
            else
            if (self::se_sp($ref) <> -1)
                return self::se_sp($ref);
            else
            if (stristr($ref, "?q=") or stristr($ref, "&q="))
                return self::se_other($ref, "q=");
            else
            if (stristr($ref, "query="))
                return self::se_other($ref, "query=");
            else
                return $ref;
        } else
            return $ref;
    }

}
