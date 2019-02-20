<?php

/**
 * Column to render ordered products preview
 */
class ProductsPreviewColumn extends CGridColumn {

    /**
     * @var string url to assets directory
     */
    public $baseUrl;

    /**
     * Register column componenets
     */
    public function init() {
        $this->baseUrl = Yii::app()->getAssetManager()->publish(
                        Yii::getPathOfAlias('mod.shop.assets'), false, -1, YII_DEBUG
                ) . '/previewColumn';

        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile($this->baseUrl . '/core.js', CClientScript::POS_END);
        $cs->registerCssFile($this->baseUrl . '/style.css');
    }

    /**
     * Renders column content
     * @param int $row
     * @param mixed $data
     */
    public function renderDataCellContent($row, $data) {
        $order = $this->grid->dataProvider->data[$row];
        echo Html::image($this->baseUrl . '/trolley.png', '', array(
            'id' => $order->id,
            'class' => 'productPreview',
        ));
    }

}
