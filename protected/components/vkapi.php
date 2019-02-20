<?php

/**
 * VK api class for vk.com social network
 *
 * @package app
 * @link http://vk.com/developers.php
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @version 1.0
 * @link http://pixelion.com.ua PIXELION CMS
 * @example <code>code</code> description
 */
class vkapi {

    var $api_secret;
    var $app_id;
    var $api_url;

    public function vkapi($app_id, $api_secret, $api_url = 'api.vk.com/api.php') {
        $this->app_id = $app_id;
        $this->api_secret = $api_secret;
        if (!strstr($api_url, 'http://'))
            $api_url = 'http://' . $api_url;
        $this->api_url = $api_url;
    }

    public function api($method, $params = false) {
        if (!$params)
            $params = array();
        $params['api_id'] = $this->app_id;
        $params['v'] = '3.0';
        $params['method'] = $method;
        $params['timestamp'] = time();
        $params['format'] = 'json';
        $params['random'] = rand(0, 10000);
        ksort($params);
        $sig = '';
        foreach ($params as $k => $v) {
            $sig .= $k . '=' . $v;
        }
        $sig .= $this->api_secret;
        $params['sig'] = md5($sig);
        $query = $this->api_url . '?' . $this->params($params);
        $res = file_get_contents($query);
        return json_decode($res, true);
    }

    private function params($params) {
        $pice = array();
        foreach ($params as $k => $v) {
            $pice[] = $k . '=' . urlencode($v);
        }
        return implode('&', $pice);
    }

}

?>