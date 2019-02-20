<?php

class BannedIP extends CApplicationComponent {

    public $userIP;
    public $params;

    public function init() {
        $this->userIP = CMS::getip();
        if ($this->verify_ip_ban()) {
            header("HTTP/1.0 403 Forbidden");
            header("HTTP/1.1 403 Forbidden");
            header("Status: 403 Forbidden");
            Yii::app()->controllerMap['bannedip'] = 'app.maintenance.MaintenanceController';
            Yii::app()->catchAllRequest = array('bannedip/bannedip');
        }
    }

    protected function verify_ip_ban() {
        Yii::import('mod.admin.models.BannedIPModel');
        $addresses = BannedIPModel::model()->findAll();
        $btime = BannedIPModel::bannedTime();
        $user_ipaddress = $this->userIP . '.';
        foreach ($addresses as $banned) {
            if (strpos($banned->ip_address, '*') === false AND $banned->ip_address{strlen($banned->ip_address) - 1} != '.') {
                $banned->ip_address .= '.';
            }

            $banned_ip_regex = str_replace('\*', '(.*)', preg_quote($banned->ip_address, '#'));
            if (preg_match('#^' . $banned_ip_regex . '#U', $user_ipaddress)) {
                if (time() < $banned->timetime || $banned->time == 0) {

                    $this->params = array(
                        'ip' => $this->userIP,
                        'reason' => $banned->reason,
                        'banned_time' => $btime[$banned->time],
                        'left_time' => CMS::display_time($banned->timetime - time()),
                        'permanently' => ($banned->time)?false:true
                    );
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }


}

