<?php

class BrandsWidget extends CWidget
{

    public $options = array();

    public function init()
    {
        $defaultOptions = array(
            'navText' => array('', ''),
            'responsiveClass' => true,
            'margin'=>20,
           // 'stagePadding'=>20,
            'responsive' => array(
                0 => array(
                    'items' => 1,
                    'nav' => false,
                    'dots' => true,
                    'center' => true,
                    'loop' => true,
                ),
                480 => array(
                    'items' => 2,
                    'nav' => false,
                    'dots' => true
                ),
                768 => array(
                    'items' => 2,
                    'nav' => false,
                    'dots' => true
                ),
                992 => array(
                    'items' => 3,
                    'nav' => false,
                    'dots' => true
                ),
                1200 => array(
                    'items' => 6,
                    'dots' => false,
                    'nav' => true,
                    'loop' => false,
                    'mouseDrag' => false,
                )
            )
        );
        $config = CJavaScript::encode(CMap::mergeArray($defaultOptions, $this->options));
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('owl.carousel');
        $cs->registerScript(__CLASS__ . '#' . $this->id, "$('#{$this->id}').owlCarousel($config);", CClientScript::POS_END);
    }

    public function run()
    {
        $model = ShopManufacturer::model()->findAll();
        $this->render($this->skin, array('model' => $model));
    }

}
