<?php

/*
  <?php
  Yii::import('ext.uploadify.UploadifyFile');
  $this->widget('ext.uploadify.EUploadifyWidget', array(
  // можно использовать как для поля модели
  'model' => new UploadifyFile,
  'attribute' => 'uploadifyFile',
  // так и просто для элемента формы
  'name' => 'UploadifyFile_uploadifyFile',
  'options' => array(
  'fileTypeExts' => '*.jpg;*.png;*.gif',
  'auto' => true,
  'preventCaching'=>false,
  'multi' => true,
  'debug'=>true,
  'buttonText' => 'Upload Images',

  )
  ));

  ?>
  <a href="javascript:$('#UploadifyFile_uploadifyFile').uploadify('upload','*')">Upload Files</a> */

class EUploadifyWidget extends CInputWidget {

    public static function actions() {
        return array(
            'upload' => 'ext.uploadify.SwfUploadAction',
        );
    }

    public $attribute;
    public $package = array();
    public $options = array();

    public function init() {



        list($this->name, $this->id) = $this->resolveNameId();
        // Set defaults package.
        if ($this->package == array()) {
            $this->package = array(
                'basePath' => dirname(__FILE__) . '/assets',
                'js' => array(
                    'jquery.uploadify' . (YII_DEBUG ? '' : '.min') . '.js',
                ),
                'css' => array(
                    (Yii::app()->controller instanceof AdminController) ? 'uploadify_admin.css' : 'uploadify.css',
                ),
                'depends' => array('jquery'),
            );
        }
        // Publish package assets. Force copy assets in debug mode.
        if (!isset($this->package['baseUrl'])) {
            $this->package['baseUrl'] = Yii::app()->getAssetManager()->publish($this->package['basePath'], false, -1, YII_DEBUG);
        }

        $baseUrl = $this->package['baseUrl'];

        $this->options['swf'] = $baseUrl . '/uploadify.swf';
        $this->options['fileObjName'] = $this->attribute;
        //$this->options['formData'] = array(Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken);
        if (!isset($this->options['uploader'])) {
            $this->options['uploader'] = '/ajax/uploadify.upload';
        }

        // fileDesc is required if fileExt set.
        if (!empty($this->options['fileTypeExts']) && empty($this->options['fileTypeDesc'])) {
            $this->options['fileTypeDesc'] = Yii::t('yiiext', 'Supported files ({fileTypeExts})', array('{fileTypeExts}' => $this->options['fileTypeExts']));
        }

        $this->registerClientScript();
    }

    /**
     * Run widget.
     */
    public function run() {
        if ($this->hasModel()) {
            echo Html::activeFileField($this->model, $this->attribute, $this->htmlOptions);
        } else {
            echo Html::fileField($this->name, $this->value, $this->htmlOptions);
        }
    }

    /**
     * @return void
     * Register CSS and Script.
     */
    protected function registerClientScript() {
        //$cssFile = (Yii::app()->controller instanceof AdminController) ? 'uploadify_admin.css' : 'uploadify.css';
        $cs = Yii::app()->getClientScript();
        //$cs->registerCssFile($this->package['baseUrl'] . "/".$cssFile);
        $cs->packages['uploadify'] = $this->package;
        $cs->registerPackage('uploadify');
        
        $cs->registerScript(__CLASS__ . '#' . $this->id, 'jQuery("#' . $this->id . '").uploadify(' . CJavaScript::encode($this->options) . ');', CClientScript::POS_READY);
    }

}
