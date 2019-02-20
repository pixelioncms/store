<?php
if (($q = Yii::app()->request->getParam('q')))
    $result = CHtml::encode($q);
?>
<div class="catalog">




    <h1><?= Yii::t('ShopModule.default', 'SEARCH_RESULT'); ?></h1>
    <?php if (count($provider->getData())) $this->renderPartial('_sorting'); ?>
    <div class="products_list">

        <?php
        $this->widget('ListView', array(
            'dataProvider' => $provider,
            'ajaxUpdate' => false,
            'template' => '{items} {pager}',
            'itemView' => '_view_grid',
            'sortableAttributes' => array(
                'name', 'price'
            ),
            'emptyText' => Yii::t('ShopModule.default', 'EMPTY_SEARCH_TEXT', array('{result}' => $result)),
            'pager' => array(
                'htmlOptions' => array('class' => 'pagination'),
                'header' => '',
                'nextPageLabel' => 'Следующая »',
                'prevPageLabel' => '« Предыдущая',
                'prevPageLabel' => '« Предыдущая',
                'prevPageLabel' => '« Предыдущая',
            )
        ));
        ?>

    </div>
</div>