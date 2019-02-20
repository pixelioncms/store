<?php

/**
 * Plugin option see: https://github.com/kartik-v/bootstrap-fileinput#bootstrap-fileinput
 */
class FileInputFull extends CInputWidget {

    public $options = array();
    public $selector = false;
    private $defaultOptions = array(
        //'showUpload' => false,
        // 'showPreview' => false,
        'browseIcon' => '<i class="icon-folder-open"></i>&nbsp;',
        'removeIcon' => '<i class="icon-delete"></i>',
        'uploadIcon' => '<i class="icon-upload"></i>',
        'previewFileIcon' => '',
        'fileActionSettings' => array(
            'uploadIcon' => '<i class="icon-upload text-info"></i>',
            'removeIcon' => '<i class="icon-delete text-danger"></i>',
            'zoomIcon' => '<i class="icon-search"></i>',
            'indicatorNew' => '<i class="icon-warning text-warning"></i>'
        ),
        'previewZoomButtonIcons' => array(
            'prev' => '<i class="fa fa-caret-left fa-lg"></i>',
            'next' => '<i class="fa fa-caret-right fa-lg"></i>',
            'toggleheader' => '<i class="fa fa-arrows-v"></i>',
            'fullscreen' => '<i class="fa fa-arrows-alt"></i>',
            'borderless' => '<i class="fa fa-external-link"></i>',
            'close' => '<i class="icon-delete"></i>'
        ),
        
            'overwriteInitial'=> false,
    'initialPreview'=> array(
        "/uploads/product/0dOLLWO.jpg",
        "/uploads/product/0GNSy6o.jpg"
    ),
    'initialPreviewAsData'=> true, // identify if you are sending preview data only and not the raw markup
    'initialPreviewFileType'=> 'image', // image is the default and can be overridden in config below
    'initialPreviewConfig'=> array(
        array('caption'=> "People-1.jpg", 'size'=> 576237, 'width'=> "120px", 'url'=> "/site/file-delete", 'key'=> 1),
        array('caption'=> "People-2.jpg", 'size'=> 932882, 'width'=> "120px", 'url'=> "/site/file-delete", 'key'=> 2), 
    ),
        //'uploadUrl' => "/file-upload-single/1", // server upload action
        //'uploadAsync' => true,
        //'maxFileCount' => 5
            //'mainClass'=> "input-group-lg"
            //'showCaption'=>false,
    );

    public function init() {
        parent::init();
        $this->defaultOptions = CMap::mergeArray(array(
                    'language' => Yii::app()->language
                        ), $this->defaultOptions);
    }

    public function run() {

        if ($this->hasModel())
            list($name, $id) = $this->resolveNameID();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'file-loading';

        if ($this->hasModel())
            echo Html::activeFileField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::fileField($name, $this->value, $this->htmlOptions);
        $this->registerScript();
    }

    protected function registerScript() {
        $lang = Yii::app()->language;
        if ($this->hasModel())
            list($name, $id) = $this->resolveNameID();
        $baseUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DS . 'assets', false, -1, YII_DEBUG);
        $min = YII_DEBUG ? '' : '.min';
        $cs = Yii::app()->getClientScript();


        $cs->registerScriptFile($baseUrl . "/js/fileinput{$min}.js", CClientScript::POS_BEGIN);
     
        if ($lang != 'en')
            $cs->registerScriptFile($baseUrl . "/js/locales/{$lang}.js", CClientScript::POS_BEGIN);
        //$cs->registerScriptFile($baseUrl . "/themes/icon/theme.js", CClientScript::POS_BEGIN);
        $cs->registerCssFile($baseUrl . "/css/fileinput{$min}.css");
        $cs->registerCssFile($baseUrl . "/css/fileinput-rtl{$min}.css");
        $config = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));

        $id = ($this->selector) ? $this->selector : $id;

        $cs->registerScript(__CLASS__ . '#' . $id, "$('#{$id}').fileinput($config);", CClientScript::POS_END);
    }

}
