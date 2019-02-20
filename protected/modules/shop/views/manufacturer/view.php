
<div>
    <div class="products_list">
        <?php
        $this->widget('Breadcrumbs', array(
            'links' => $this->breadcrumbs,
        ));
        ?>

        <h1><?php echo CHtml::encode($this->dataModel->name); ?></h1>

        <?php if (!empty($this->dataModel->description)): ?>
            <div>
                <?php echo $this->dataModel->description ?>
            </div>
        <?php endif ?>

        <div class="actions">
            <?php
            echo Yii::t('ShopModule.core', 'Сортировать:');
            echo CHtml::dropDownList('sorter', Yii::app()->request->url, array(
                Yii::app()->request->removeUrlParam('/shop/manufacturer/index', 'sort') => '---',
                Yii::app()->request->addUrlParam('/shop/manufacturer/index', array('sort' => 'price')) => Yii::t('ShopModule.core', 'Сначала дешевые'),
                Yii::app()->request->addUrlParam('/shop/manufacturer/index', array('sort' => 'price.desc')) => Yii::t('ShopModule.core', 'Сначала дорогие'),
                    ), array('onchange' => 'applyCategorySorter(this)'));
            ?>

            <?php
            $limits = array(Yii::app()->request->removeUrlParam('/shop/manufacturer/index', 'per_page') => $this->allowedPageLimit[0]);
            array_shift($this->allowedPageLimit);
            foreach ($this->allowedPageLimit as $l)
                $limits[Yii::app()->request->addUrlParam('/shop/manufacturer/index', array('per_page' => $l))] = $l;

            echo Yii::t('ShopModule.core', 'На странице:');
            echo CHtml::dropDownList('per_page', Yii::app()->request->url, $limits, array('onchange' => 'applyCategorySorter(this)'));
            ?>
        </div>

        <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $provider,
            'ajaxUpdate' => false,
            'template' => '{items} {pager} {summary}',
            'itemView' => 'shop.views.category._grid',
            'sortableAttributes' => array(
                'name', 'price'
            ),
        ));
        ?>
    </div>
</div><!-- catalog_with_sidebar end -->
