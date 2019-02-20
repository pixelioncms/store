    <?php     echo Html::form(); ?>  
<div id="shop-sort-par_page">
    <?php


    $limits = array(10=>$this->allowedPageLimit[0]);
    array_shift($this->allowedPageLimit);
    foreach ($this->allowedPageLimit as $l)
        $limits[$l] = $l;

    echo Yii::t('ShopModule.default', 'OUTPUT_ON');
    echo Html::dropDownList('per_page', Yii::app()->request->getParam('per_page'), $limits, array('onchange' => 'applyCategorySorter(this)'));
    echo Yii::t('ShopModule.default', 'товаров');
    ?>
</div>
<div id="shop-sort-sorter">
    <?php
    echo Yii::t('ShopModule.default', 'SORT');
    echo Html::dropDownList('sort', Yii::app()->request->getParam('sort'), array(
        'price' => Yii::t('ShopModule.default', 'SORT_BY', 0),
        'price.desc' => Yii::t('ShopModule.default', 'SORT_BY', 1),
        'date_create.desc' => Yii::t('ShopModule.default', 'SORT_BY', 2),
            ), array('onchange' => 'applyCategorySorter(this)','empty'=>Yii::t('app', 'EMPTY_LIST')));
    ?>
</div>
<div id="shop-sort-view">
    <?= Html::htmlButton('Grid', array('class'=>'button btn-small','name'=>'view','value'=>'')); ?>
    <?= Html::htmlButton('list', array('class'=>'button btn-small','name'=>'view','value'=>'list')); ?>
    <a class="button btn-small" <?php if ($itemView === '_product_list') echo 'class="active"'; ?> href="<?php echo Yii::app()->request->addUrlParam('/shop/category/view', array('view' => 'list')) ?>"><span class="icon-paragraph-justify-2 icon-medium"></span></a>
    <a class="button btn-small" <?php if ($itemView === '_product') echo 'class="active"'; ?> href="<?php echo Yii::app()->request->removeUrlParam('/shop/category/view', 'view') ?>"><span class="icon-grid icon-medium"></span></a>
</div>

    <?php     echo Html::endForm(); ?> 