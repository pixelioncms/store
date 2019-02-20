<?php

class CodeMirrorWidget extends CInputWidget {
    public $target;
    public $mode;
    public $config = array();

    public function init() {

        $this->registerAssets();
    }

    public function run() {
        
        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if ($this->hasModel())
            echo Html::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textArea($name, $this->value, $this->htmlOptions);

        
        
        $config = CJavaScript::encode($this->config);
          Yii::app()->clientScript->registerScript($this->getId(), "
              
      /*var editor2 = CodeMirror.fromTextArea(document.getElementById('{$this->target}'), {
          mode: {name: 'css', globalVars: true},
         lineNumbers: true,
        //autoCloseTags: true
      });*/

                  ");
    }

    public function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $min = (YII_DEBUG) ? '' : '.min';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile($baseUrl . "/js/codemirror.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/php/php.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/htmlmixed/htmlmixed.js", CClientScript::POS_HEAD);
            //$cs->registerScriptFile($baseUrl . "/mode/javascript/javascript.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/css/css.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/clike/clike.js", CClientScript::POS_HEAD);
            $cs->registerCssFile($baseUrl . '/css/codemirror.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }
    
    
    
    public static function registerAssets2($mode) {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile($baseUrl . "/js/codemirror.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/addon/edit/matchbrackets.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/php/php.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/css/css.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/xml/xml.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/htmlmixed/htmlmixed.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/javascript/javascript.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/clike/clike.js", CClientScript::POS_HEAD);
            $cs->registerCssFile($baseUrl . '/css/codemirror.css');
            

        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
