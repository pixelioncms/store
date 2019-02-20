<?php
/**
 * 
 * https://github.com/RobinHerbots/Inputmask/releases
 */
class InputAllMask extends CInputWidget {

    public $options = array();
    public $defaultOptions = array();

    function run() {
        $this->defaultOptions = array(
            'mask' => '+3 (999) 999-99-99',
        );
        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        
        if(isset($this->htmlOptions['class'])){
            $this->htmlOptions['class'] = $this->htmlOptions['class'];
        }else{
            $this->htmlOptions['class'] = 'form-control';
        }
        if ($this->hasModel())
            echo Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textField($name, $this->value, $this->htmlOptions);

        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . "/js/inputmask.min.js", CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/js/jquery.inputmask.min.js", CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/js/inputmask.phone.extensions.min.js", CClientScript::POS_END);
        $js = "$('.phone').inputmask({$options});";
        $cs->registerScript(__CLASS__ . '#' . $id, $js, CClientScript::POS_END);
    }

}