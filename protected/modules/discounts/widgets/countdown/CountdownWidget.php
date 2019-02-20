<?php

class CountdownWidget extends CWidget
{

    public $model;

    public function run()
    {

        if (Yii::app()->hasModule('discounts')) {

            if ($this->model->appliedDiscount && strtotime($this->model->discountEndDate) < time()) {
                $this->registerScript();
                $this->render($this->skin);
            }
        }

    }

    public function registerScript()
    {
        $time = strtotime($this->model->discountEndDate) * 1000;
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $cs->registerScript('CountdownWidget', "
    $(function(){
        $.fn.countdown_day = function(number) {
            var num = number % 10;
            if (num == 1){
                return '" . CMS::GetFormatWord('CountdownWidget.default', 'DAYS', 0) . "';
            } else if (num > 1 && num < 5){
                return '" . CMS::GetFormatWord('CountdownWidget.default', 'DAYS', 1) . "';
            } else {
                return '" . CMS::GetFormatWord('CountdownWidget.default', 'DAYS', 2) . "';
            }
        };

        $('#countdown').countdown({
            timestamp	: " . $time . ",
            callback: function (days, hours, minutes, seconds) {
                $('.date .key').html(days);
                $('.hour .key').html(hours);
                $('.minutes .key').html(minutes);
                $('.seconds .key').html(seconds);
                //$('.date .value').html($.fn.countdown_day(days));

             
            }
         });
	});", CClientScript::POS_HEAD);
        $cs->registerScriptFile($assetsUrl . "/jquery.countdown.js", CClientScript::POS_END);
    }

}
