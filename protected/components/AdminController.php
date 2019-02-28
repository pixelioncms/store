<?php

/**
 * Базовый класс для админ контроллеров.
 *
 * @uses Controller
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package components
 * @link http://pixelion.com.ua PIXELION CMS
 */
class AdminController extends Controller
{


    /**
     * Controller icon
     * @var string
     */
    public $icon;
    public $isAdminController = true;

    /**
     *
     * @var string
     */
    public $layout = 'mod.admin.views.layouts.main';

    /**
     *
     * @var array
     */
    public $menu = array();

    /**
     * Отоброжение кнопкок.
     * @var boolean
     */
    public $topButtons = null;

    /**
     *
     * @var array
     */
    protected $_addonsMenu = array();

    /**
     *
     * @var array
     */
    protected $_sidebarWidgets = array();

    public function init()
    {

        Yii::app()->user->loginUrl = array('/admin/auth');
        $this->module->initAdmin();

        if (!empty($this->icon)) {
            $this->icon = Html::tag('i', array('class' => $this->icon), '', true);
        }
        //if (Yii::app()->request->getParam('lofiversion')) {
        //    $this->layout = 'mod.admin.views.layouts.lofiversion';
        //}
        parent::init();

    }

    public function getRedirectTabsHash()
    {
        return Yii::app()->user->getState('redirectTabsHash');
    }

    public function filters()
    {
        return array('rights');
    }

    /**
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action)
    {

        // Allow only authorized users access
        if (Yii::app()->user->isGuest && get_class($this) !== 'AuthController') {
            Yii::app()->request->redirect($this->createUrl('/admin/auth'));
        }

        Yii::app()->clientScript->registerScriptFile($this->baseAssetsUrl . "/js/common.js", CClientScript::POS_END);
        Yii::import('mod.admin.components.yandexTranslate');
        Yii::app()->clientScript->registerScript('commonjs', '
            var translate_object_url = "' . Yii::app()->settings->get('app', 'translate_object_url') . '";
            var yandex_translate_apikey = "' . yandexTranslate::API_KEY . '";
            var common = window.CMS_common || {};
            common.langauge="' . Yii::app()->language . '";
            common.token="' . Yii::app()->request->csrfToken . '";
            common.isDashboard=true;
            common.message=' . CJavaScript::encode($this->commonJsMessages), CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($this->baseAssetsUrl . "/css/pixelion-icons.css");

        return true;
    }

    /**
     * action
     */
    public function actionCreate()
    {
        $this->actionUpdate(true);
    }

    public function ___actionRemoveFile($path, $filename)
    {
        if (isset($path) && isset($filename)) {
            if (file_exists(Yii::getPathOfAlias($path) . DS . $filename)) {
                $this->setNotify(Yii::t('app', 'FILE_DELETE_SUCCESS'));
                //unlink($filepath);
            }
        }
    }

    /**
     * Action воостановление настроек по умолчанию
     * @param object $model
     */
    public function actionResetSettings($model, $ref = false)
    {
        if (isset($model)) {
            $mdl = new $model;
            Yii::app()->settings->set($mdl->getModuleId(), $mdl::defaultSettings());
            $this->setNotify(Yii::t('app', 'SUCCESS_RESET_SETTINGS'));
            if ($ref) {
                $this->redirect(array($ref));
            } else {
                $this->redirect(array('/admin/' . $mdl->getModuleId() . '/settings'));
            }
        }
    }

    /**
     * @return string admin/<module>/<controller>/<action>
     */
    public function getUniqueId()
    {
        if (strpos($this->id, '/')) {
            $ex = explode('/', $this->id);
            $flag = true;
        } else {
            $flag = false;
        }
        return ($this->module && $flag) ? $ex[0] . '/' . $this->module->getId() . '/' . $ex[1] : $this->id;
    }

}
