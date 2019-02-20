<?php

/**
 * languages https://www.tinymce.com/download/language-packages/
 */
class TinymceArea extends CInputWidget {

    protected $assetsPath;
    protected $assetsUrl;
    protected $assetsBasePath;
    protected $assetsBaseUrl;
    public $options = array();
    public $selector;
    public function run() {

        if ($this->assetsPath === null) {
            $this->assetsPath = Yii::getPathOfAlias("mod.forum.assets");
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }


        if ($this->assetsBasePath === null) {
            $this->assetsBasePath = Yii::getPathOfAlias("ext.tinymce.assets");
        }
        if ($this->assetsBaseUrl === null) {
            $this->assetsBaseUrl = Yii::app()->assetManager->publish($this->assetsBasePath, false, -1, YII_DEBUG);
        }

        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = $this->htmlOptions['class'];
        else
            $this->htmlOptions['class'] = "editor-post";

        if ($this->hasModel())
            echo Html::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textArea($name, $this->value, $this->htmlOptions);

        $assetsName = str_replace("/assets/", "", $this->assetsBaseUrl);
        $moxiemanagerPath = Yii::getPathOfAlias("webroot.assets.{$assetsName}.plugins.moxiemanager");
        CMS::setChmod($moxiemanagerPath . DS . 'api.php', 0640);

        $this->registerScript();
    }

    public function registerScript() {

        if (file_exists(Yii::getPathOfAlias(Yii::app()->getModule(Yii::app()->controller->module->id)->uploadAliasPath))) {
            $moxiemanager_rootpath = Yii::app()->getModule(Yii::app()->controller->module->id)->uploadPath;
        } else {
            $moxiemanager_rootpath = '/uploads/content';
        }
//die(Yii::app()->controller->module->assetsUrl . '/forum-data.css');
        $cs = Yii::app()->clientScript;
        $defaultOptions = array(
            'selector' => $this->selector,
            'language' => Yii::app()->language,
            'contextmenu' => "link image inserttable | cell row column deletetable",
            'resize' => false,
            'branding' => false,
            'paste_enable_default_filters' => false,
            'paste_filter_drop' => false,
            'relative_urls' => false,
            'document_base_url' => '/',
            'templates' => array(
                array(
                    'title' => 'Alert success',
                    'content' => '<div class="alert alert-success" role="alert">My alert content</div>'
                ),
                array(
                    'title' => 'Alert danger',
                    'content' => '<div class="alert alert-danger" role="alert">My alert content</div>'
                ),
                array(
                    'title' => 'Alert info',
                    'content' => '<div class="alert alert-info" role="alert">My alert content</div>'
                ),
                array(
                    'title' => 'Alert warning',
                    'content' => '<div class="alert alert-warning" role="alert">My alert content</div>'
                ),
                array(
                    'title' => 'Label default',
                    'content' => '<span class="badge badge-secondary">Default</span>'
                ),
                array(
                    'title' => 'Label primary',
                    'content' => '<span class="badge badge-primary">Primary</span>'
                ),
                array(
                    'title' => 'Label success',
                    'content' => '<span class="badge badge-success">Success</span>'
                ),
                array(
                    'title' => 'Label info',
                    'content' => '<span class="badge badge-info">Info</span>'
                ),
                array(
                    'title' => 'Label warning',
                    'content' => '<span class="badge badge-warning">Warning</span>'
                ),
                array(
                    'title' => 'Label danger',
                    'content' => '<span class="badge badge-danger">Danger</span>'
                ),
            ),
            'table_class_list' => array(
                array('title' => 'None', 'value' => ''),
                array('title' => 'Striped', 'value' => 'table table-striped'),
                array('title' => 'Bordered', 'value' => 'table table-bordered'),
                array('title' => 'Bordered & Striped', 'value' => 'table table-bordered table-striped'),
                array('title' => 'Hover', 'value' => 'table table-hover'),
                array('title' => 'Condensed', 'value' => 'table table-condensed'),
            ),
            'image_title' => true,
            'image_class_list' => array(
                array('title' => 'None', 'value' => ''),
                array('title' => 'Rounded', 'value' => 'img-rounded'),
                array('title' => 'Rounded & Responsive', 'value' => 'img-rounded img-responsive'),
                array('title' => 'Circle', 'value' => 'img-circle'),
                array('title' => 'Circle & Responsive', 'value' => 'img-circle img-responsive'),
                array('title' => 'Thumbnail', 'value' => 'img-thumbnail'),
                array('title' => 'Thumbnail & Responsive', 'value' => 'img-thumbnail img-responsive'),
                array('title' => 'Responsive', 'value' => 'img-responsive'),
            ),
        );

        if (false) {
            $defaultOptions['plugins'] = array(
                "autoresize advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen textcolor",
                "insertdatetime media table contextmenu paste moxiemanager",
            );
            $defaultOptions['moxiemanager_rootpath'] = $moxiemanager_rootpath;
            $defaultOptions['moxiemanager_language'] = Yii::app()->language;
            $defaultOptions['moxiemanager_skin'] = 'custom';
            $defaultOptions['toolbar'] = "insertfile undo redo | styleselect fontsizeselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image";
        } else {
            $defaultOptions['menubar']= false;
            $defaultOptions['plugins'] = array(
                "autoresize advlist autolink lists link image anchor textcolor",

            );
            $defaultOptions['statusbar']= false;
            $defaultOptions['toolbar'] = "undo redo | fontsizeselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image";
        }
        // array_push($defaultOptions, array('content_css'=>Yii::app()->createAbsoluteUrl(Yii::app()->controller->module->assetsUrl . '/forum-data.css')));
        if (file_exists(Yii::getPathOfAlias("mod.forum.assets") . DS . 'forum-data.css')) {
            $defaultOptions['content_css'] = Yii::app()->createAbsoluteUrl(Yii::app()->controller->module->assetsUrl . '/forum-data.css');
            // $defaultOptions['content_css'] = Yii::app()->controller->module->assetsUrl . '/forum-data.css';
            /* $defaultOptions['content_css'] = array(
              Yii::app()->createAbsoluteUrl(Yii::app()->controller->getBaseAssetsUrl() . '/css/bootstrap.min.css'),
              Yii::app()->createAbsoluteUrl(Yii::app()->controller->getAssetsUrl() . '/css/theme.css'),
              ); */

            // }else{
            //     die('err');
        }
//var_dump(is_dir($this->assetsBaseUrl));
//die;
        //if (is_dir($this->assetsBaseUrl)) {
        $cs->registerScriptFile($this->assetsBaseUrl . '/tinymce.min.js', CClientScript::POS_HEAD);
        $cs->registerScript('tinymce-forum'.$this->id, 'tinymce.init(' . CJSON::encode(CMap::mergeArray($defaultOptions, $this->options)) . ');', CClientScript::POS_HEAD);
        // } else {
        //     throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        // }
    }

}
