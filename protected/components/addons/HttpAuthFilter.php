<?php

/**
 * HttpAuthFilter performs authorization checks using http authentication
 *
 * By enabling this filter, controller actions can be limited to a couple of users.
 * It is very simple, supply a list of usernames and passwords and the controller actions 
 * will be restricted to only those. Nothing fancy, it just keeps out users.
 * 
 * To specify the authorized users specify the 'users' property of the filter
 * Example 
 * <code>
 * public function filters(){
 *      return array(
 *          array(
 *              'app.addons.HttpAuthFilter',
 *              'users'=>array('root'=>'password'), 
 *              'realm'=>'Admin section'
 *          )  
 *      );
 * }
 * </code>
 * The default section for the users property is 'root'=>'password' change it
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage addons
 * @uses CFilter
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class HttpAuthFilter extends CFilter {

    /**
     * @return array list of authorized users/passwords
     */
    public $users = array('root' => 'pass');
    public $json = false;
    public $auth_login;
    public $auth_pw;

    /**
     * @return string authentication realm
     */
    public $realm = 'Authentication needed';

    /**
     * Performs the pre-action filtering.
     * @param CFilterChain the filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    public function preFilter($filterChain) {
        if ($this->isAuthenticated()) {
            Yii::log('not auth', 'error', 'application');
            $this->authenticate();
            // } else {
            //      Yii::log('auth', 'error', 'application');
            //     return true;
        }else{
            return true;
        }
    }

    public function authenticate() {
        header("WWW-Authenticate: Basic realm=\"" . $this->realm . "\"");
        header('HTTP/1.0 401 Unauthorized');

        ///var_dump($_SERVER['PHP_AUTH_USER']);
        //die;
        // if ($this->json) {
        //  echo CJSON::encode(array(
        //      'error' => 401,
        //       'message' => Yii::t('yii', 'You are not authorized to perform this action.')
        //   ));
        //} else {
        throw new CHttpException(401, Yii::t('error', '401'));
        // }
        exit;
    }

//User::encodePassword(
    public function isAuthenticated() {
        //  $_SERVER['PHP_AUTH_USER']=NULL;
        //  $_SERVER['PHP_AUTH_PW']=NULL;

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            //if ($_SERVER['PHP_AUTH_USER'] && $_SERVER['PHP_AUTH_PW']) {
        
                //$session=new CHttpSession;
                //$session->open();
                //$session['http_auth_user']=$_SERVER['PHP_AUTH_USER']; 
                //$session['http_auth_pw']=$_SERVER['PHP_AUTH_PW']; 
                //$httpd_username = ($_SESSION['http_auth_user'])?$_SESSION['http_auth_user']: $_SERVER['PHP_AUTH_USER'];
                //$httpd_password = ($_SESSION['http_auth_pw'])?$_SESSION['http_auth_pw']: $_SERVER['PHP_AUTH_PW'];
                //  Yii::app()->session['http_auth_user'] =$_SERVER['PHP_AUTH_USER'];
                // $httpd_username = filter_var($_SERVER['PHP_AUTH_USER'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW);
                // $httpd_password = filter_var($_SERVER['PHP_AUTH_PW'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW);
                $httpd_username = $_SERVER['PHP_AUTH_USER'];
                $httpd_password = $_SERVER['PHP_AUTH_PW'];
                Yii::log($httpd_password . '-' . $httpd_username, 'error', 'application');
                if (isset($this->users[$httpd_username])) {
                    if ($this->users[$httpd_username] === $httpd_password) {
                        Yii::app()->user->setState('http_auth', true);
 
                        return true;
                    } else {
                        Yii::log('no found errrrrr', 'error', 'application');
                        Yii::app()->user->setState('http_auth', false);
                        return false;
                    }
                } else {
                    Yii::app()->user->setState('http_auth', false);
                    return false;
                }
            //} else {
            //    Yii::app()->user->setState('http_auth', false);
            //    Yii::log('no found $_SERVER', 'error', 'application');
            //    return false;
            //}
        } else {
            Yii::app()->user->setState('http_auth', false);
            return false;
        }
        return false;
    }

}
