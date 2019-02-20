<?php

Yii::import('zii.widgets.jui.CJuiAutoComplete');

class JuiAutoComplete extends CJuiAutoComplete {

    public $methodChain;

    public function run() {
        list($name, $id) = $this->resolveNameID();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->hasModel())
            echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo CHtml::textField($name, $this->value, $this->htmlOptions);

        if ($this->sourceUrl !== null)
            $this->options['source'] = CHtml::normalizeUrl($this->sourceUrl);
        else
            $this->options['source'] = $this->source;

        $options = CJavaScript::encode($this->options);
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, "jQuery('#{$id}').autocomplete($options){$this->methodChain};");
    }

}
