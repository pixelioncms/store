<?php
/**
 * 
 * https://goodies.pixabay.com/jquery/tag-editor/demo.html
 */
class TagEditor extends CInputWidget {

    public $options = array();
    public $defaultOptions = array();

    function run() {
        $this->defaultOptions = array(
            'placeholder' => Yii::t('default','ADD_TAG'),
        );
        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if ($this->hasModel())
            echo Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textField($name, $this->value, $this->htmlOptions);

        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
       // $cs = Yii::app()->getClientScript();
       // $cs->registerScriptFile(Yii::app()->controller->baseAssetsUrl . '/js/jquery.tag-editor.min.js');
        
        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);

        $cs = Yii::app()->getClientScript();

        $cs->registerScriptFile($baseUrl . "/js/jquery.tag-editor.min.js",CClientScript::POS_END);
        $cs->registerCssFile($baseUrl . "/css/jquery.tag-editor.css");
        
        
        $js = "$('#{$id}').tagEditor({$options});";
        $cs->registerScript(__CLASS__ . '#' . $id, $js,CClientScript::POS_END);
    }

}