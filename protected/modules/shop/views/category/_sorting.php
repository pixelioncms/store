<?php if ($this->beginCache('_sorting', array('duration' => Yii::app()->settings->get('app','cache_time')))) { ?>


    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <?php
            $limits = array(Yii::app()->request->removeUrlParam('/shop/category/view', 'per_page') => $this->allowedPageLimit[0]);
            array_shift($this->allowedPageLimit);
            foreach ($this->allowedPageLimit as $l)
                $limits[Yii::app()->request->addUrlParam('/shop/category/view', array('per_page' => $l))] = $l;
            ?>
            <span class="hidden-xs"><?= Yii::t('ShopModule.default', 'OUTPUT_ON');?></span>
            <?php
            echo Html::dropDownList('per_page', Yii::app()->request->url, $limits, array('onchange' => 'window.location = $(this).val();'));
            ?>
            <span class="hidden-xs"><?= Yii::t('ShopModule.default', 'товаров');?></span>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <?php
           // echo Yii::t('ShopModule.default', 'SORT');
            echo Html::dropDownList('sorter', Yii::app()->request->url, array(
                Yii::app()->request->removeUrlParam('/shop/category/view', 'sort') => Yii::t('ShopModule.default', 'SORT', 1),
                Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'price')) => Yii::t('ShopModule.default', 'SORT_BY', 0),
                Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'price.desc')) => Yii::t('ShopModule.default', 'SORT_BY', 1),
                Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'date_create.desc')) => Yii::t('ShopModule.default', 'SORT_BY', 2),
                    ), array('onchange' => 'window.location = $(this).val();'));
            ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <div id="shop-sort-view" class="btn-group fr">
                <a class="btn btn-xs btn-default <?php if ($itemView === '_list') echo 'btn-info active'; ?> hidden-xs" href="<?php echo Yii::app()->request->addUrlParam('/shop/category/view', array('view' => 'list')) ?>">Списком</a>
                <a class="btn btn-xs btn-default <?php if ($itemView === '_grid') echo 'btn-info active'; ?>" href="<?php echo Yii::app()->request->removeUrlParam('/shop/category/view', 'view') ?>">Сеткой</a>
            </div>
        </div>
    </div>
    

<div class="clearfix"></div>


<?php
$this->endCache();
} ?>