<?php

/**
 * Базовый класс контроллеров.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses RController
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
Yii::import('mod.users.UsersModule');

class Controller extends RController
{


    public $dataModel = null;

    public function adminModel()
    {
        $result = array();
        $data = $this->dataModel;
        if (isset($data)) {
            if (isset($data->scenario)) {
                if ($data->scenario == 'update') {
                    // $result['route_delete'] = $this->dataModel->getDeleteUrl();
                    // $result['route_update'] = $this->dataModel->getUpdateUrl();
                    // $result['route_switch'] = $this->dataModel->getSwitchUrl();
                    $result['route_create'] = $data->getCreateUrl();
                    $result['create_btn'] = Html::link(Yii::t(ucfirst($data::MODULE_ID) . 'Module.default', 'CREATE'), $data->getCreateUrl());
                }
            }
        }
        return $data;
    }

    public $compareIds = array();
    public $wishlistIds = array();
    public $commonJsMessages = array();
    public $isAdminController = false;
    protected $_assetsUrl = false;
    protected $_packageAssetsUrl = false;
    protected $_baseAssetsUrl = false;
    protected $_messages;
    public $breadcrumbs = array();
    public $pageName;
    public $pageKeywords;
    public $pageDescription;
    private $_pageTitle;
    public $layout = '';
    public $currentModule;
    public $canonical;

    public function setJson(array $array)
    {
        header('Content-Type: application/json; charset="' . Yii::app()->charset . '"');
        echo CJSON::encode($array);
        Yii::app()->end();
    }

    public function getFirstCityId()
    {
        Yii::import('mod.contacts.models.ContactsCites');

        $cr = new CDbCriteria;
        $cr->limit = 1;
        $city = ContactsCites::model()->find($cr);
        return $city->id;
    }

    public function getCacheTime()
    {
        return !YII_DEBUG ? 0 : 3600;
    }


    /**
     * @return string Показывает информацию о сгенерируемой страницы.
     */
    public function getPageGen()
    {
        $sql_stats = Yii::app()->db->getStats();
        return Yii::t('default', 'PAGE_GEN', array(
            '{time}' => sprintf("%1f", Yii::getLogger()->getExecutionTime()),
            '{memory}' => CMS::files_size(memory_get_peak_usage()),
            '{db_query}' => $sql_stats[0],
            '{db_time}' => sprintf("%0.2f", $sql_stats[1]),
        ));
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array('LayoutBehavior' => array('class' => 'app.behaviors.LayoutBehavior')));
    }

    protected function beforeRender($view)
    {
        if (!$this->isAdminController) {
            if (Yii::app()->hasModule('seo')) {
                Yii::import('mod.seo.models.Redirects');
                $redirect = Redirects::model()->published()->findByAttributes(array(
                    'url_from' => Yii::app()->request->url
                ));
                if ($redirect) {
                    $this->redirect(array($redirect->url_to), true, 301);
                }
            }
        }
        $this->initLayout();

        return parent::beforeRender($view);
    }

    protected function beforeAction($action)
    {

        $cs = Yii::app()->clientScript;


        //For iPad: 72x72
        //For iPhone: 57x57
        //For iPhone 4 Retina display: 114x114
        if (!Yii::app()->request->isAjaxRequest) {
            $appletouch = array('57x57', '60x60', '72x72', '76x76', '114x114', '120x120', '144x144', '152x152', '180x180');
            foreach ($appletouch as $size) {
                if (file_exists(Yii::getPathOfAlias("current_theme.assets.images") . DS . "apple-touch-icon-{$size}.png")) {
                    $cs->registerLinkTag('apple-touch-icon', NULL, $this->assetsUrl . "/images/apple-touch-icon-{$size}.png", NULL, array('sizes' => $size));
                } elseif (file_exists(Yii::getPathOfAlias("current_theme.assets.images") . DS . "apple-touch-icon.png")) {
                    $cs->registerLinkTag('apple-touch-icon', NULL, $this->assetsUrl . "/images/apple-touch-icon.png", NULL);
                }
            }


            if (count(Yii::app()->languageManager->getLanguages()) > 1) {
                foreach (Yii::app()->languageManager->getLanguages() as $lang) {
                    $link = ($lang->is_default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();

                    if ($lang->is_default) {
                        $cs->registerLinkTag('alternate', null, Yii::app()->request->hostInfo . $link, null, array('hreflang' => str_replace('_', '-', $lang->locale)));
                    } else {
                        $cs->registerLinkTag('alternate', null, Yii::app()->request->hostInfo . $link, null, array('hreflang' => str_replace('_', '-', $lang->locale)));
                    }

                }
            }

            $favicons = array('16x16', '32x32', '48x48', '192x192');
            foreach ($favicons as $size) {
                if (file_exists(Yii::getPathOfAlias("current_theme.assets.images") . DS . "favicon-{$size}.png")) {
                    $cs->registerLinkTag('icon', "image/png", $this->assetsUrl . "/images/favicon-{$size}.png", NULL, array('sizes' => $size));
                }
            }
            $cs->registerScript('commonjs', "
        var common = window.CMS_common || {};
        common.token = '" . Yii::app()->request->csrfToken . "';
        common.language = '" . Yii::app()->language . "';
        common.assetsUrl = '" . $this->assetsUrl . "';
        common.debug = " . CJavaScript::encode(YII_DEBUG) . ";
        common.message = " . CJavaScript::encode($this->commonJsMessages) . ";
        ", CClientScript::POS_HEAD);
            $cs->registerCssFile($this->baseAssetsUrl . "/css/pixelion-icons.css");


            if (file_exists(Yii::getPathOfAlias("current_theme.assets") . DS . "manifest.json")) {
                $cs->registerLinkTag('manifest', NULL, $this->assetsUrl . "/manifest.json");
            } else {
                //$cs->registerLinkTag('manifest', NULL, "/manifest.json");
            }
            $cs->registerMetaTag(null, null, null, array(
                'charset' => Yii::app()->charset
            ));
            $cs->registerMetaTag(Yii::app()->name . ' CMS', 'author');
            $cs->registerMetaTag(Yii::app()->name . ' CMS ' . Yii::app()->version, 'generator');
            if (Yii::app()->themeManager->get('google_theme_color') && !$this->isAdminController) {
                $cs->registerMetaTag(Yii::app()->themeManager->get('google_theme_color'), 'theme-color');
            }
        }
        //manifest.json


        $cs->registerScriptFile($this->baseAssetsUrl . "/js/common.js", CClientScript::POS_END);


        return parent::beforeAction($action);

    }


    protected function afterRender($view, &$output)
    {
        if (!Yii::app()->request->isAjaxRequest && !preg_match("#" . base64_decode('e2NvcHlyaWdodH0=') . "#", $output) && !preg_match("/print/", $this->layout)) {
            $this->renderPartial('app.maintenance.layouts.alert', array('content' => Yii::t('app', base64_decode('Tk9fQ09QWVJJR0hU'))), false, true);
            Yii::app()->end();
        }
        if (!Yii::app()->hasModule('seo')) {
            $cs = Yii::app()->clientScript;
            if ($this->pageDescription !== null) {
                $cs->registerMetaTag($this->pageDescription, 'description', null, null, 'description');
            }
            if ($this->pageKeywords !== null) {
                $cs->registerMetaTag($this->pageKeywords, 'keywords', null, null, 'keywords');
            }
        }

        parent::afterRender($view, $output);
    }

    public function setPageTitle($title)
    {
        $this->_pageTitle = $title;
    }

    /**
     * Register assets file of theme
     * @return string
     */
    private function registerAssets()
    {
        $assets = Yii::getPathOfAlias('current_theme.assets');
        $url = Yii::app()->getAssetManager()->publish($assets, false, -1, YII_DEBUG);

        //if ($this->_baseAssetsUrl === null) {
        $this->_baseAssetsUrl = Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('app.assets'), false, -1, YII_DEBUG
        );
        // }
        // if ($this->_packageAssetsUrl === null) {
        $this->_packageAssetsUrl = Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('app.packages'), false, -1, YII_DEBUG
        );
        // }
        $this->_assetsUrl = $url;
    }


    /**
     * @return string
     */
    public function getAssetsUrl()
    {
        return $this->_assetsUrl;
    }

    /**
     * @return string
     */
    public function getPackageAssetsUrl()
    {
        return $this->_packageAssetsUrl;
    }

    /**
     * @return string
     */
    public function getBaseAssetsUrl()
    {
        return $this->_baseAssetsUrl;
    }

    public function printer($title, $content, $date)
    {
        if (Yii::app()->request->getParam('print')) {
            $this->layout = '//layouts/print';
            $this->pageTitle = 'Печать';


            $this->render('//layouts/_print', array(
                'title' => $title,
                'content' => $content,
                'date' => CMS::date($date)
            ));
            Yii::app()->end();
        }
    }

    public function error404($msg = null)
    {
        if (!$msg) {
            $msg = Yii::t('error', '404');
        }
        throw new CHttpException(404, $msg);
    }

    public function init()
    {
        /* $request = Yii::app()->request->requestUri;
          $code = 404;
          $message = 'Страница не найдена';

          // Проверяем, если есть в урле index.php или ?r=, то кидаем 404 ошибку
          if ((strpos($request, 'index.php') !== false) || (strpos($request, '?r=') !== false || (strpos($request, '&') !== false) || (strpos($request, '?') !== false)))
          {
          // Если это не контроллер по-умолчанию, то кидаем 404 ошибку обычным способом
          if (Yii::app()->controller->id !== Yii::app()->defaultController)
          throw new CHttpException($code, $message);

          // Если это контроллер по-умолчанию, кидаем 404 ошибку необычным способом.
          header('HTTP/1.0 404 Not Found');
          // Отображаем стандартное представление ошибки
          $this->render(Yii::app()->errorHandler->errorAction, array(
          'code' => $code,
          'message'=> $message
          ));
          // Выходим из приложения
          Yii::app()->end();
          } */
        $user = Yii::app()->user;
        $langManager = Yii::app()->languageManager;

        if (!$user->isGuest && $user->language) {
            if ($user->getLanguage() != $langManager->default->code) {
                $getLang = $langManager->getById($user->getLanguage())->code;
                Yii::app()->language = $getLang;
                $strpos = strpos(Yii::app()->request->requestUri, '/' . $getLang);
                if ($strpos === false) {
                    if ($langManager->default->code != $getLang) {
                        if ($this->isAdminController)
                            $this->redirect("/{$getLang}/admin");
                        else
                            $this->redirect('/' . $getLang);
                    }
                }
            } else {
                Yii::app()->language = $langManager->active->code;
            }
        } else {
            Yii::app()->language = $langManager->active->code;
        }

        if (Yii::app()->hasModule('wishlsit')) {

            $wishListComponent = new WishListComponent();
            $this->wishlistIds = $wishListComponent->getIds();
        }
        if (Yii::app()->hasModule('compare')) {
            $compareComponent = new CompareProducts();
            $this->compareIds = $compareComponent->getIds();
        }


        $this->currentModule = (isset($this->module)) ? $this->module->id : false;
        $theme = Yii::app()->theme->name;
        Yii::setPathOfAlias("current_theme", Yii::getPathOfAlias("webroot.themes.{$theme}"));
        $this->backup();
        $this->registerAssets();


        //Yii::app()->clientScript->coreScriptPosition=CClientScript::POS_END;
        Yii::app()->clientScript->packages = array(
            //'coreScriptPosition'=>CClientScript::POS_END,
            'bootstrap' => array(
                'baseUrl' => $this->packageAssetsUrl . '/bootstrap/',
                'js' => array(
                    YII_DEBUG ? 'popper/popper.js' : 'popper/popper.min.js',
                    // 'dist/dropdown.js',
                    // 'dist/util.js',
                    YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    //YII_DEBUG ? 'js/bootstrap.bundle.js' : 'js/bootstrap.bundle.min.js',
                ),
                'css' => array(YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'),
                'position' => CClientScript::POS_END,
                //'jsOptions'=>array('async'=>'async'),
                'depends' => array('jquery', 'jquery.ui'),
            ),
            'cookie' => array(
                'baseUrl' => $this->packageAssetsUrl . '/cookie/',
                'js' => array('jquery.cookie.js'),
                'depends' => array('jquery'),
            ),
            'owl.carousel' => array(
                'baseUrl' => $this->packageAssetsUrl . '/owl.carousel/',
                'css' => array(
                    YII_DEBUG ? 'assets/owl.carousel.min.css' : 'assets/owl.carousel.css',
                    YII_DEBUG ? 'assets/owl.theme.default.min.css' : 'assets/owl.theme.default.css'
                ),
                'js' => array(YII_DEBUG ? 'owl.carousel.js' : 'owl.carousel.min.js'),
                'depends' => array('jquery'),
            ),
        );


        if (Yii::app()->getModule('stats') && !$this->isAdminController) {
            if (Yii::app()->hasComponent('stats')) { // && Yii::app()->controller->action->id != 'placeholder'
                $stats = Yii::app()->stats;
                $stats->record();
            }
        }
        $this->commonJsMessages = array(
            'error' => array(
                '404' => Yii::t('error', '404')
            ),
            'cancel' => Yii::t('app', 'CANCEL'),
            'send' => Yii::t('app', 'SEND'),
            'delete' => Yii::t('app', 'DELETE'),
            'save' => Yii::t('app', 'SAVE'),
            'close' => Yii::t('app', 'CLOSE'),
            'ok' => Yii::t('app', 'OK'),
            'loading' => Yii::t('app', 'LOADING'),
        );

        parent::init();
    }

    private function backup()
    {
        Yii::app()->db->export();
        $security = Yii::app()->settings->get('security');
        if ($security->backup_db && Yii::app()->user->isSuperuser) {
            if ($security->backup_time_cache < time()) {
                /* Записываем новое текущие время + указанное время */
                Yii::app()->settings->set('security', array('backup_time_cache' => time() + $security->backup_time));
                /* Делаем Backup */
                Yii::app()->db->export();
            }
        }
    }

    /**
     *
     * @return string
     */
    public function getPageTitle()
    {
        $title = Yii::app()->settings->get('app', 'site_name');
        if (!empty($this->_pageTitle)) {
            $title = $this->_pageTitle .= ' ' . Yii::app()->settings->get('seo', 'separation') . ' ' . $title;
        }
        return $title;
    }

    /**
     * Изменяя или удаление копирайты системы, Вы нарушаете соглашение договора.
     * Проверка наличие копирейта в шаблонах
     */
    public function processOutput($output)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->registerCss('copyright', '
            #pixelion span.cr-logo{display:inline-block;font-size:17px;padding: 0 0 0 45px;position:relative;font-family:Pixelion,Montserrat;font-weight:normal;line-height: 40px;}
            #pixelion span.cr-logo:after{font-weight:normal;content:"\f002";left:0;top:0;position:absolute;font-size:37px;font-family:Pixelion;}
        ');
            if ($this->isAdminController) {
                $copyright = Yii::app()->getCopyright();
            } else {
                $copyright = '<a href="//pixelion.com.ua/" id="pixelion" target="_blank"><span>' . Yii::t('default', 'PIXELION') . '</span> &mdash; <span class="cr-logo">PIXELION</span></a>';
            }
            $output = str_replace(base64_decode('e2NvcHlyaWdodH0='), $copyright, $output);
        }
        return parent::processOutput($output);
    }

    /**
     *
     * @param string $view
     * @param array $data
     * @param bool $return
     * @param bool $processOutput
     * @return parent::render
     */
    public function render($view, $data = null, $return = false, $processOutput = false)
    {
        if (Yii::app()->request->isAjaxRequest === true) {
            parent::renderPartial($view, $data, $return, $processOutput);
        } else {
            parent::render($view, $data, $return);
        }
    }


    /**
     *
     * @param string $message
     */
    public function addFlashMessage($message)
    {
        $currentMessages = Yii::app()->user->getFlash('messages');

        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

    /**
     *
     * @param string $message
     */
    public function setFlashMessage($message)
    {
        $currentMessages = Yii::app()->user->getFlash('messages');
        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

    public function setNotify($message, $type = 'info')
    {
        $currentMessages = Yii::app()->user->getFlash($type);
        if (!is_array($currentMessages))
            $currentMessages = array();
        $messages = array($type => $message);
        Yii::app()->user->setFlash('notify', CMap::mergeArray($currentMessages, $messages));
    }

    public function performAjaxValidation($model, $formid)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $formid) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
