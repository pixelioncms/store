<?php

/**
 * API
 * https://github.com/snapappointments/bootstrap-select/releases
 */
class SelectInput extends CInputWidget {

    public $options = array();
    public $data = array();
    public $isReturn=false;
    public function run() {

        list($name, $id) = $this->resolveNameID();
        $defaultOptions = array('iconBase'=> '','tickIcon'=> 'icon-check');
        $config = CJavaScript::encode(CMap::mergeArray($defaultOptions, $this->options));
        $cs = Yii::app()->getClientScript();
        self::registerScript();
        $cs->registerScript(__CLASS__ . '#' . $id, "$('#$id').selectpicker($config);",CClientScript::POS_END);


        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->hasModel() && !$this->isReturn)
            echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
        else
            echo Html::dropdownlist($name, $this->value, $this->data, $this->htmlOptions);
        
    }

    public static function registerScript() {
        $lang = Yii::app()->languageManager->active->locale;
        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $min = YII_DEBUG ? '' : '.min';
        $cs = Yii::app()->getClientScript();

        $cs->registerScriptFile($baseUrl . "/js/bootstrap-select{$min}.js",CClientScript::POS_END);
        if ($lang != 'en')
            $cs->registerScriptFile($baseUrl . "/js/i18n/defaults-{$lang}{$min}.js",CClientScript::POS_END);
        $cs->registerCssFile($baseUrl . "/css/bootstrap-select{$min}.css");

    }

}
