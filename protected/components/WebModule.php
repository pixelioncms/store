<?php

/**
 * Базовый класс модулей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CWebModule
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class WebModule extends CWebModule
{

    protected $_rules = array();
    protected $_config = array();
    protected $_assetsUrl = null;
    protected $adminSidebarMenu;
    protected $_icon;
    protected $_info = array();
    protected $_adminMenu = array();
    public $baseModel;
    public $sidebar = false;
    public $uploadPath;
    public $uploadAliasPath = null;
    public $configFiles = array();

    public function getAdminSidebarMenu()
    {
        return false;
    }

    public function getIsActive($route = false)
    {
        if (is_array($route)) {
            foreach ($route as $r) {

                if ($r) {
                    $reg = preg_quote($r, '/');
                    if (preg_match("/$reg/i", Yii::app()->controller->route)) {
                        $match = true;
                    } else {
                        $match = false;
                    }
                } else {
                    $match = false;
                }
            }
        } else {
            if ($route) {
                $reg = preg_quote($route, '/');
                if (preg_match("/$reg/i", Yii::app()->controller->route)) {
                    $match = true;
                } else {
                    $match = false;
                }
            } else {
                $match = false;
            }
        }

        if (isset(Yii::app()->controller->module)) {
            return (Yii::app()->controller->module->id == $this->id && $match) ? true : false;
        } else {
            return false;
        }
    }

    public function beforeControllerAction($controller, $action)
    {

        if ($controller instanceof AdminController && Yii::app()->user->isSuperuser) {
            Yii::app()->setComponents(array(
                'errorHandler' => array(
                    'errorAction' => 'admin/errors/index',
                ),
            ));
        }

        if (parent::beforeControllerAction($controller, $action)) {

            // if(Yii::app()->hasComponent('access')){
            if (!Yii::app()->access->check($controller->module->access)) {
                throw new CHttpException(401);
            }
            // }
            return true;
        } else {
            return false;
        }
    }

    public function getRules()
    {
        return $this->_rules;
    }

    public function initAdmin()
    {
        $this->uploadAliasPath = "webroot.uploads.content.{$this->id}";
        $this->uploadPath = "/uploads/content/{$this->id}";
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
            'admin.widgets.*',
        ));
        $this->defaultController = 'admin';
    }

    /**
     * Publish admin stylesheets,images,scripts,etc.. and return assets url
     *
     * @access public
     * @return string Assets url
     */
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('mod.' . $this->id . '.assets'), false, -1, YII_DEBUG
            );
        }
        return $this->_assetsUrl;
    }

    /**
     * Set assets url
     *
     * @param string $url
     * @access public
     * @return void
     */
    public function setAssetsUrl($url)
    {
        $this->_assetsUrl = $url;
    }


    /**
     * Method will be called after module installed
     */
    public function afterInstall()
    {
        if ($this->uploadAliasPath && !file_exists(Yii::getPathOfAlias($this->uploadAliasPath)))
            CFileHelper::createDirectory(Yii::getPathOfAlias($this->uploadAliasPath), 0777);


        if (file_exists(Yii::getPathOfAlias("mod.{$this->id}.sql") . DS . 'dump.sql')) {

            if(Yii::app()->settings->get('database', 'tables_engine')){
                Yii::app()->db->importSqlFile(Yii::getPathOfAlias("mod.{$this->id}.sql") . DS . 'dump.sql', Yii::app()->settings->get('database', 'tables_engine'));
            }else{
                Yii::app()->db->importSqlFile(Yii::getPathOfAlias("mod.{$this->id}.sql") . DS . 'dump.sql');
            }

        }


        if (isset($this->configFiles)) {
            foreach ($this->configFiles as $name => $class) {
                $configClass = new $class;
                if (method_exists($configClass, 'defaultSettings')) {
                    Yii::app()->settings->set($name, $configClass::defaultSettings());
                }
            }
        }


        Yii::app()->cache->flush();
        Yii::app()->widgets->clear();
        return true;
    }

    /**
     * Method will be called after module removed
     */
    public function afterUninstall()
    {
        if ($this->uploadAliasPath && !file_exists(Yii::getPathOfAlias($this->uploadAliasPath)))
            CFileHelper::removeDirectory(Yii::getPathOfAlias($this->uploadAliasPath), array('traverseSymlinks' => true));

        if (file_exists(Yii::getPathOfAlias("webroot.uploads.attachments.{$this->id}")))
            CFileHelper::removeDirectory(Yii::getPathOfAlias("webroot.uploads.attachments.{$this->id}"), array('traverseSymlinks' => true));


        if (isset($this->configFiles)) {
            foreach ($this->configFiles as $name => $class) {
                Yii::app()->settings->clear($name);

            }
        }


        Yii::app()->cache->flush();
        Yii::app()->widgets->clear();


        $moduleName = ucfirst($this->id) . '.';

        $cmdItems = Yii::app()->db->createCommand()->select('*')->from('{{authitem}}');
        $cmdItems->where(array('like', 'name', '%' . $moduleName . '%'));
        $cmdItemsChilds = Yii::app()->db->createCommand()->select('*')->from('{{authitemchild}}');
        $cmdItemsChilds->where(array('like', 'child', '%' . $moduleName . '%'));

        $resultItems = $cmdItems->queryAll();
        if ($resultItems) {
            foreach ($resultItems as $authitem) {
                Yii::app()->db->createCommand()->delete('{{authitem}}', 'name=:name', array(':name' => $authitem['name']));
            }
        }
        $resultItemsChilds = $cmdItemsChilds->queryAll();
        if ($resultItemsChilds) {
            foreach ($resultItemsChilds as $authitemchild) {
                Yii::app()->db->createCommand()->delete('{{authitemchild}}', 'child=:child', array(':child' => $authitemchild['child']));
            }
        }

        return true;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_config))
            return $this->_config[$name];
        else
            return parent::__get($name);
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (CException $e) {
            $this->_config[$name] = $value;
        }
    }

    public function getIcon()
    {
        return $this->_icon;
    }

    public function setIcon($icon)
    {
        $this->_icon = $icon;
    }

    public function getAuthor()
    {
        return 'info@pixelion.com.ua';
    }

    public function getName()
    {
        $name = ucfirst($this->id);
        return Yii::t("{$name}Module.default", 'MODULE_NAME');
    }

    public function getDescription()
    {
        $name = ucfirst($this->id);
        return Yii::t("{$name}Module.default", 'MODULE_DESC');
    }

    public function getAdminHomeUrl()
    {
        return array('/admin/' . $this->id . '/default/index');
    }

}
