<?php

trait TinymceTrait
{

    public function registerScript()
    {
        $moxiemanager_rootpath = '/uploads/content';
        if (isset(Yii::app()->controller->module)) {
            if (file_exists(Yii::getPathOfAlias(Yii::app()->getModule(Yii::app()->controller->module->id)->uploadAliasPath))) {
                $moxiemanager_rootpath = Yii::app()->getModule(Yii::app()->controller->module->id)->uploadPath;
            }
        }

        $cs = Yii::app()->clientScript;


        //$pluginAssetsPath = Yii::app()->assetManager->publish(dirname(__FILE__) . DS . 'assets' . DS . 'cms_plugins', false, -1, YII_DEBUG);


        $defaultOptions = array(
            'menubar' => true,
            'selector' => ".editor",
            'language' => Yii::app()->language,
            'contextmenu' => "link image inserttable | cell row column deletetable",
            'sticky_offset' => 51,
            'image_advtab' => true,
            'plugins' => array(
                "codesample stickytoolbar autoresize template advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen textcolor",
                "insertdatetime media table moxiemanager contextmenu paste pagebreak",
            ),

            'external_plugins' => array(
                'moxiemanager' => $this->assetsUrl . '/cms_plugins/moxiemanager/plugin.min.js',
                'stickytoolbar' => $this->assetsUrl . '/cms_plugins/stickytoolbar/plugin.js',
               // 'pixelion' => $this->assetsUrl . '/cms_plugins/pixelion/plugin.js',
            ),
            'codesample_languages' => array(
                array('text' => 'HTML/XML', 'value' => 'markup'),
                array('text' => 'JavaScript', 'value' => 'javascript'),
            ),
            //'hidden_input'=> false,
            //'content_security_policy'=> "default-src 'self'",
            'theme' => 'modern',
            'mobile' => array('theme' => 'mobile'),
            'resize' => true,
            'branding' => false,
            'paste_enable_default_filters' => false,
            'paste_filter_drop' => false,
            'moxiemanager_rootpath' => $moxiemanager_rootpath,
            'moxiemanager_language' => Yii::app()->language,
            'moxiemanager_skin' => 'custom',
//pixelion |
            'toolbar' => "codesample | insertfile undo redo | styleselect fontsizeselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | pagebreak template",
            'relative_urls' => false,
            'document_base_url' => '/',
            'image_prepend_url' => "/uploads",

            'templates' => array(
                array(
                    'title' => "Editor Details",
                    //'url'=> "/product/smartfon-apple-iphone-6s-32gb-gold",
                    'url' => "/test.php",
                    //'url'=> "/admin/app/ajax/test",
                    'description' => "Adds Editor Name and Staff ID"
                ),
                array(
                    'title' => 'Alert success',
                    'content' => '<div class="alert alert-success" role="alert">My alert content</div>',
                    'description' => "Bootstrap alert success"
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
                    'title' => 'Badge secondary',
                    'content' => '<span class="badge badge-secondary">Secondary</span>'
                ),
                array(
                    'title' => 'Badge primary',
                    'content' => '<span class="badge badge-primary">Primary</span>'
                ),
                array(
                    'title' => 'Badge success',
                    'content' => '<span class="badge badge-success">Success</span>'
                ),
                array(
                    'title' => 'Badge info',
                    'content' => '<span class="badge badge-info">Info</span>'
                ),
                array(
                    'title' => 'Badge warning',
                    'content' => '<span class="badge badge-warning">Warning</span>'
                ),
                array(
                    'title' => 'Badge danger',
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
                array('title' => 'Rounded', 'value' => 'img-rounded rounded'),
                array('title' => 'Rounded & Responsive', 'value' => 'img-rounded img-responsive img-fluid'),
                array('title' => 'Circle', 'value' => 'img-circle rounded-circle'),
                array('title' => 'Circle & Responsive', 'value' => 'img-circle img-responsive img-fluid'),
                array('title' => 'Thumbnail', 'value' => 'img-thumbnail'),
                array('title' => 'Thumbnail & Responsive', 'value' => 'img-thumbnail img-responsive img-fluid'),
                array('title' => 'Responsive', 'value' => 'img-responsive img-fluid'),
            ),
        );

        // if (file_exists(Yii::getPathOfAlias("current_theme.assets.css") . DS . 'tinymce.css')) {
        //   $defaultOptions['content_css'][] = Yii::app()->controller->baseAssetsUrl . '/css/pixelion-icons.css';
        // $defaultOptions['content_css'][] = Yii::app()->getModule('admin')->getAssetsUrl() . '/css/typography.css';
        //$defaultOptions['content_css'][] = Yii::app()->getModule('admin')->getAssetsUrl() . '/css/dashboard.css';
        //$defaultOptions['content_css'] = Yii::app()->getModule('admin')->getAssetsUrl() . '/css/typography.css';
        /// }
        if (file_exists(Yii::getPathOfAlias("current_theme.assets.css") . DS . 'tinymce.css')) {
            // $defaultOptions['content_css'] = Yii::app()->createAbsoluteUrl(Yii::app()->controller->getAssetsUrl() . '/css/tinymce.css');//todo bug with multilanguage
            // $defaultOptions['content_css'] = Yii::app()->controller->getAssetsUrl() . '/css/tinymce.css';
            /* $defaultOptions['content_css'] = array(
              Yii::app()->createAbsoluteUrl(Yii::app()->controller->getBaseAssetsUrl() . '/css/bootstrap.min.css'),
              Yii::app()->createAbsoluteUrl(Yii::app()->controller->getAssetsUrl() . '/css/theme.css'),
              ); */
        }
        $packagesAsset = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('app.packages'), false, -1, YII_DEBUG);

        $defaultOptions['content_css'] = $packagesAsset . '/bootstrap/css/bootstrap.min.css';

        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/tinymce.min.js', CClientScript::POS_END);
            $cs->registerScript('tinymce' . $this->getId(), 'tinymce.init(' . CJSON::encode(CMap::mergeArray($defaultOptions, $this->options)) . ');', CClientScript::POS_END);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
