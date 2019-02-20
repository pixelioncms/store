<?php

/**
 * Widget add to compare module for shop.
 * 
 * @version 1.0
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @copyright Copyright &copy; 2016 Andrew Semenov
 * 
 * Example:
 * <code>
 * $this->widget('mod.compare.widgets.CompareWidget',array('pk'=>$model->primaryKey));
 * </code>
 * 
 */
Yii::import('mod.compare.components.CompareProducts');

class CompareWidget extends Widget {

    public $registerFile = array('compare.js');
    public $pk;
    public $linkOptions = array();
    public $isAdded = false;

    public function init() {
        if (!YII_DEBUG)
            Yii::import('mod.compare.CompareModule');
        if (is_null($this->pk))
            throw new CException(Yii::t('default', 'ERROR_PK_ISNULL'));

        $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {
        $compareComponent = new CompareProducts();
        $this->isAdded = (in_array($this->pk, $compareComponent->getIds())) ? true : false;

        $linkOptions = array();
        $class = ($this->isAdded) ? 'added' : '';
        $textType = ($this->isAdded) ? 1 : 0;
        $linkOptions['class'] = '';

        if (isset($this->linkOptions['class'])) {
            $linkOptions['class'] .=(isset($this->linkOptions['class'])) ? $this->linkOptions['class'] : '';
        }

        $linkOptions['id'] = 'compare-' . $this->pk;
        $linkOptions['class'] .= ' ' . $class;
        $this->linkOptions = $linkOptions;


        $this->render($this->skin, array());
    }

}
