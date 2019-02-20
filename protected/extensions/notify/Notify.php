<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * 
 * </code>
 * 
 * @package widgets.other
 * @uses CComponent
 */
class Notify extends CComponent {

    public static function register() {
        $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile($assetsUrl . "/bootstrap-notify.min.js",CClientScript::POS_END);
    }

}
