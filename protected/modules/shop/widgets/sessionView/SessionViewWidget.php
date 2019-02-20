<?php

/**
 * SessionViewWidget
 *
 * @package widgets.modules.shop
 * @uses CWidget
 */
class SessionViewWidget extends CWidget
{
    public $options = array();
    public $current_id = null;

    public function init()
    {
        parent::init();


        $defaultOptions = array(
            'navText' => array('', ''),
            'responsiveClass' => true,
            'margin' => 0,
            'stagePadding' => 0,

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
                    'items' => 2,
                    'nav' => false,
                    'dots' => true
                ),
                1200 => array(
                    'items' => 3,
                    'nav' => true,
                    'loop' => false,
                    'mouseDrag' => false,
                ),
                1440 => array(
                    'items' => 5,
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
        $list = array();
        $session = Yii::app()->session->get('views');
        if (!empty($session)) {
            $ids = array_unique($session);
            if ($this->current_id) {
                $key = array_search($this->current_id, $ids);
                unset($ids[$key]);
            }
            $list = ShopProduct::model()->findAllByPk($ids);
        }
        $this->render($this->skin, array('model' => $list));
    }

}
