<?php

/**
 * 
 * <b>Пример:</b>
 * <code>
 * $this->widget('ext.manual.manual');
 * </code>
 * 
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua/ PIXELION CMS - Система управление сайтом
 * @package widgets.other.manual
 * @uses CWidget 
 */
class manual extends CWidget {

    public $title;

    public function init() {
        $this->title = Yii::t('app', 'MANUAL_HELP');
        $this->registerScript($this->title);
    }

    public function run() {
        $module = Yii::app()->controller->module->id;
        $path = Yii::getPathOfAlias("mod.{$module}.manual") . "/manual.md";
        if (file_exists($path)) {
            $text = file_get_contents($path);
            $markdown = new CMarkdown;
            echo CHtml::openTag('div', array('id' => 'manual'));
            echo $markdown->transform($text);
            echo CHtml::closeTag('div');
            echo CHtml::link('<span class="icon-medium icon-info-2 "></span> ' . $this->title, 'javascript:void(0)', array('onClick' => '$("#manual").dialog("open")'));
        }
    }

    /**
     * @access private
     */
    private function registerScript($params) {

        $cs = Yii::app()->getClientScript();
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);

        if (is_dir($assets)) {

            $cs->registerScript('manual', '
            
$("#manual").dialog({
autoOpen:false,
resizable:false,
modal:true,
width:"80%",
height:500,
title:"' . $params . '"
}).css({overflow:"auto"});

');

            $cs->registerCssFile($baseUrl . '/manual.css');
        }else
            throw new Exception(Yii::t('multifile - Error: Couldn\'t find assets folder to publish.'));
    }

}

?>