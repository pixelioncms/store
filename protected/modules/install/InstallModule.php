<?php

class InstallModule extends CWebModule {

    protected $_assetsUrl = false;

    public function init() {
        $baseAssetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('app.assets'), false, -1, YII_DEBUG
        );

        $this->_assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . DS . 'assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;


        $packagesAsset = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('app.packages'), false, -1, YII_DEBUG);
        //Yii::app()->clientScript->coreScriptPosition=CClientScript::POS_END;
        Yii::app()->clientScript->packages = array(
            //'coreScriptPosition'=>CClientScript::POS_END,
            'bootstrap' => array(
                'baseUrl' => $packagesAsset . '/bootstrap/',
                'js' => array(
                    YII_DEBUG ? 'popper/popper.js' : 'popper/popper.min.js',
                    // 'dist/dropdown.js',
                    // 'dist/util.js',
                    YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                ),
                'css' => array(YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'),
                //'position'=>CClientScript::POS_END,
                //'jsOptions'=>array('async'=>'async'),
                'depends' => array('jquery', 'jquery.ui'),
            ),
            'cookie' => array(
                'baseUrl' => $packagesAsset . '/cookie/',
                'js' => array('jquery.cookie.js'),
                'depends' => array('jquery'),
            ),
            'owl.carousel' => array(
                'baseUrl' => $packagesAsset . '/owl.carousel/',
                'css' => array(
                    YII_DEBUG ? 'assets/owl.carousel.min.css' : 'assets/owl.carousel.css',
                    YII_DEBUG ? 'assets/owl.theme.default.min.css' : 'assets/owl.theme.default.css'
                ),
                'js' => array(YII_DEBUG ? 'owl.carousel.min.js' : 'owl.carousel.js'),
                'depends' => array('jquery'),
            ),
        );


        //$cs->registerCssFile($baseAssetsUrl . '/css/v4/bootstrap.min.css');
        $cs->registerCssFile($this->_assetsUrl . '/css/install.css');
        $cs->registerCssFile($baseAssetsUrl . '/css/pixelion-icons.css');
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bootstrap');
      //  $cs->registerScriptFile($baseAssetsUrl . '/js/v4/bootstrap.min.js');
    }

}
