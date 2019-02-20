<?php

/**
 * Plugin option see: https://github.com/kartik-v/bootstrap-fileinput#bootstrap-fileinput
 */
class FileInput extends CInputWidget {

    public $options = array();
    public $selector = false;
    private $defaultOptions = array();

    public function init() {
        parent::init();
        $this->defaultOptions = array(
            'theme' => 'pixelion',
            'showUpload' => false,
            'showPreview' => false,
            'frameClass' =>'img-thumbnail',
            'removeTitle' => Yii::t('app', 'DELETE'),
            'browseIcon' => '<i class="icon-folder-open"></i>&nbsp;',
            'removeIcon' => '<i class="icon-delete"></i>',
            'uploadIcon' => '<i class="icon-upload"></i>',
            //
            'previewFileIcon' => '',
            'fileActionSettings' => array(
                'uploadIcon' => '<i class="icon-upload text-info"></i>',
                'removeIcon' => '<i class="icon-delete text-danger"></i>',
                'zoomIcon' => '<i class="icon-search"></i>',
                'indicatorNew' => '<i class="icon-warning text-warning"></i>',
                'removeClass'=>'btn btn-sm btn-default',
                'zoomClass'=>'btn btn-sm btn-default',
                'dragIcon'=> '<i class="icon-menu"></i>',
            ),
            'previewZoomButtonIcons' => array(
                'prev' => '<i class="fa fa-caret-left fa-lg"></i>',
                'next' => '<i class="fa fa-caret-right fa-lg"></i>',
                'toggleheader' => '<i class="fa fa-arrows-v"></i>',
                'fullscreen' => '<i class="icon-laptop"></i>',
                'borderless' => '<i class="icon-external-link"></i>',
                'close' => '<i class="icon-delete"></i>'
            ),
        );
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
            $this->htmlOptions['class'] = 'icon';

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


        $cs->registerScriptFile($baseUrl . "/js/fileinput{$min}.js",CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/themes/pixelion/theme.js",CClientScript::POS_END);
        if ($lang != 'en')
            $cs->registerScriptFile($baseUrl . "/js/locales/{$lang}.js",CClientScript::POS_END);

        $cs->registerCssFile($baseUrl . "/css/fileinput{$min}.css");
        $config = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));

        $id = ($this->selector) ? $this->selector : $id;

        $cs->registerScript(__CLASS__ . '#' . $id, "$('#{$id}').fileinput($config);", CClientScript::POS_END);
    }

}
