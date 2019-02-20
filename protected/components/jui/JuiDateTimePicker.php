<?php

/**
 * CJuiDateTimePicker class file.
 *
 * @author Anatoly Ivanchin <van4in@gmail.com>
 */
Yii::import('zii.widgets.jui.CJuiDatePicker');

class JuiDateTimePicker extends CJuiDatePicker {

    const ASSETS_NAME = '/jquery-ui-timepicker-addon';

    public $mode = 'datetime';

    public function init() {
        if (!in_array($this->mode, array('date', 'time', 'datetime')))
            throw new CException('unknow mode "' . $this->mode . '"');
        if (!isset($this->language))
            $this->language = Yii::app()->language;
        return parent::init();
    }

    public function run() {
        list($name, $id) = $this->resolveNameID();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];
        else
            $this->htmlOptions['name'] = $name;
        if (Yii::app()->controller instanceof AdminController) {
            $this->htmlOptions['class'] = 'form-control';
            $this->htmlOptions['style'] = 'width:auto';
        }
        if ($this->hasModel())
            echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo CHtml::textField($name, $this->value, $this->htmlOptions);

        $this->defaultOptions = array(
            'timeOnlyTitle' => Yii::t('JuiDateTimePicker.JuiDateTimePicker','SELECT_TIME'),
            'timeText' => Yii::t('JuiDateTimePicker.JuiDateTimePicker','TIME'),
            'hourText' => Yii::t('JuiDateTimePicker.JuiDateTimePicker','HOUR'),
            'minuteText' => Yii::t('JuiDateTimePicker.JuiDateTimePicker','MINUTES'),
        );


        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions,$this->options));

        $js = "jQuery('#{$id}').{$this->mode}picker($options);";


        if (isset($this->language)) {
            $this->registerScriptFile($this->i18nScriptFile);
            $js = "jQuery('#{$id}').{$this->mode}picker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$this->language}'], {$options}));";
        }

        $cs = Yii::app()->getClientScript();

        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DS . 'assets', false, -1, YII_DEBUG);
        $cs->registerCssFile($assets . self::ASSETS_NAME . '.css');
        $cs->registerScriptFile($assets . self::ASSETS_NAME . '.js', CClientScript::POS_END);

       // $cs->registerScript(__CLASS__, $this->defaultOptions ? "jQuery.{$this->mode}picker.setDefaults({$options});" : '');
        $cs->registerScript(__CLASS__ . '#' . $id, $js);
    }

}
