<?php

/**
 * 
 * @url widget index.php?r=main/ajax/widget.callback
 * @example 
 * 
 * Add to UserController
 * 
 * public function actions() {
 *      return array(
 *          'widget.' => 'mod.users.widgets.webcam.Webcam',
 *      );
 *  }
 */
class Webcam extends CWidget {

    public static function actions() {
        return array(
            'webcam' => 'mod.users.widgets.webcam.actions.WebcamAction',
        );
    }

    protected $assetsPath;
    protected $assetsUrl;

    public function init() {
        parent::init();
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $this->registerClientScript();
    }

    public function run() {
        //$this->render('webcam');
        echo Html::link('Сделать снимок в веб-камеры','javascript:webcam();');
    }

    protected function registerClientScript() {
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/webcam.js', CClientScript::POS_HEAD);
            $cs->registerScriptFile($this->assetsUrl . '/js/widget-webcam.js', CClientScript::POS_HEAD);
              $cs->registerCssFile($this->assetsUrl . '/css/webcam.css');
             $cs->registerScript('webcam', "
                 
    function webcam(){
        $('body').append('<div id=\"window-webcam\"></div>');
        $('#window-webcam').dialog({
            modal: true,
            resizable: false,
            width:'680',
            height:'auto',
            open:function(){
                var that = this;

                $.ajax({
                    type:'GET',
                    url:'/index.php?r=users/profile/widget.webcam',
                    data:{},
                    success:function(data){
                       //Webcam.attach('#webcam');
                       
                        $(that).html(data);

                    }
                });
            },
            close: function (event, ui) {
                $(this).remove();
                Webcam.reset(); //завершаем работу веб-камеры.
            },
            buttons:[
                {
                    text:'Отмена',
                    click:function(){
                        $(this).dialog('close');
                    }
                }]
                        
        });
    }

", CClientScript::POS_END);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}