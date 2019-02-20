<?php
/**
 * 
 * https://github.com/RobinHerbots/Inputmask/releases
 */
class InputMask extends CInputWidget {

    public $options = array();
    public $defaultOptions = array();

    public function run() {

        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id'])) {
            $id = $this->htmlOptions['id'];
            //$this->setId($id);
        }else{
            $this->htmlOptions['id'] = $id;
        }
        if(isset($this->htmlOptions['class'])){
            $this->htmlOptions['class'] = $this->htmlOptions['class'];
        }else{
            $this->htmlOptions['class'] = 'form-control';
        }
        if ($this->hasModel())
            echo Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textField($name, $this->value, $this->htmlOptions);

    }

    public function init() {

        $this->defaultOptions = array(
            'mask' => '+9 (999) 999-99-99',
        );

        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . "/js/inputmask.min.js", CClientScript::POS_END);
        //$cs->registerScriptFile($baseUrl . "/js/jquery.inputmask.min.js", CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/js/jquery.inputmask.bundle.min.js", CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/js/inputmask.phone.extensions.min.js", CClientScript::POS_END);
        $js = "$('#{$this->getid()}').inputmask({$options});";
        $cs->registerScript(__CLASS__ . '#' . $this->getid(), $js, CClientScript::POS_END);
    }



}