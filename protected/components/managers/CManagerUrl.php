<?php

/**
 * CManagerUrl
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @uses CUrlManager
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class CManagerUrl extends CUrlManager
{
    private $_rules=array();
    public $actdDoubleUrl = '301';

    /**
     * @var string Default:'index.php';Имя файла входного скрипта(точки входа)
     */
    public $scriptNameEntry = 'index.php';

    /**
     * проверяет является ли URL дубликатом и исправляет ситуацию в зависимости от настроект в main.php
     * @param type $request
     * @return boolean
     * @throws CHttpException
     */
    /* public function doubleUrl($request) {
      if (Yii::app()->urlManager->showScriptName === false && preg_match('~^/' . preg_quote($this->scriptNameEntry) . '(.*)~i', $request->requestUri, $matches)) {
      if ($this->actdDoubleUrl == '301') {
      if (empty($matches[1])) {
      $matches[1] = '/';
      }
      Yii::app()->request->redirect($matches[1], true, 301);
      } elseif ($this->actdDoubleUrl == '404') {
      throw new CHttpException(404, Yii::t('yii', 'Unable to resolve the request "{route}".', array('{route}' => $request->requestUri)));
      }
      return false;
      }
      return true;
      } */

    /**
     * функция родителя. Перед ее выполнением добавляется проверка с помощью функции addressedDoubleUrl()
     * @param type $request
     * @return type
     */
    /* public function parseUrl($request) {
      if ($request->requestUri === '/') {
      Yii::app()->urlmanager->useStrictParsing = false;
      }//отключает обязательное использование суффикса, если используется "пустое"(''=>'site/index) правило в urlManager [Фикс Yii бага]
      if ($this->doubleUrl($request)) {
      return parent::parseUrl($request);
      }
      } */

    /**
     * Init
     * @access public
     */
    public function init()
    {

        $this->_loadModuleUrls();
        parent::init();
    }

    /**
     * Create url based on current language.
     * @param mixed $route
     * @param array $params
     * @param string $ampersand
     * @param boolean $respectLang
     * @access public
     * @return string
     */
    public function createUrl($route, $params = array(), $ampersand = '&', $respectLang = true)
    {
        $result = parent::createUrl($route, $params, $ampersand);

        if ($respectLang === true) {
            $langPrefix = Yii::app()->languageManager->getUrlPrefix();
            if ($langPrefix)
                $result = '/' . $langPrefix . $result;
        }

        return $result;
    }

    /**
     * Scan each module dir and include routes.php
     * Add module urls at the beginning of $config['urlManager']['rules']
     * @access protected
     */
    protected function _loadModuleUrls()
    {
        $cacheKey = 'url_manager';
        $rules = Yii::app()->cache->get($cacheKey);
        if (YII_DEBUG || !$rules) {
            $rules = array();

            $modules = Yii::app()->getModules();
            foreach ($modules as $mid => $module) {
                $moduleClass = Yii::app()->getModule($mid);
                if (isset($moduleClass->rules)) {

                    $rules = array_merge($moduleClass->rules, $rules);
                }
            }

            Yii::app()->cache->set($cacheKey, $rules, Yii::app()->settings->get('app', 'cache_time'));
        }
        $this->rules = array_merge($rules, $this->rules);
    }

}
