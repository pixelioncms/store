<script>
    $(function () {
        $(document).on('change', '#sorter', function (e) {
            var uri = $(this).val();
            console.log($(this).val());
            $.fn.yiiListView.update('shop-products', {
                url: uri,
                data: {sort:'price.desc'}
            });
            history.pushState({}, $('title').val(), uri);
            return false;
        });
    });
</script>

<div class="clearfix filters-container">
    <div class="row">



        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">

            <span class="hidden-md hidden-sm"><?= Yii::t('ShopModule.default', 'VIEW'); ?> </span>
            <?php
            $sorter[Yii::app()->request->removeUrlParam('/shop/category/view', 'sort')] = Yii::t('ShopModule.default', 'SORT_BY', 0);
            $sorter[Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'price'))] = Yii::t('ShopModule.default', 'SORT_BY', 1);
            $sorter[Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'price.desc'))] = Yii::t('ShopModule.default', 'SORT_BY', 2);
            $sorter[Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'date_create.desc'))] = Yii::t('ShopModule.default', 'SORT_BY', 3);
            $active = Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => Yii::app()->request->getParam('sort')));
            $this->widget('ext.bootstrap.selectinput.SelectInput', array(
                'name' => 'sorter',
                'data' => $sorter,
                'options' => array(
                    'mobile' => CMS::isModile(),
                    'width' => CMS::isModile() ? false : 'auto'
                ),
                'htmlOptions' => array(
                    'options' => array($active => array('selected' => true)),
                    //'onchange' => 'window.location = $(this).val();',
                    'data-style' => CMS::isModile() ? '' : 'btn-xs',
                    'class' => ''
                )
            ));
            ?>


        </div><!-- /.col -->
        <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">



            <?php
            $limits = array(Yii::app()->request->removeUrlParam('/shop/category/view', 'per_page') => $this->allowedPageLimit[0]);
            array_shift($this->allowedPageLimit);
            foreach ($this->allowedPageLimit as $l) {
                $active = Yii::app()->request->addUrlParam('/shop/category/view', array('per_page' => Yii::app()->request->getParam('per_page')));
                $limits[Yii::app()->request->addUrlParam('/shop/category/view', array('per_page' => $l))] = $l;
            }
            ?>
            <span class="hidden-md hidden-sm"><?= Yii::t('ShopModule.default', 'OUTPUT_ON'); ?> </span>
            <?php
            $this->widget('ext.bootstrap.selectinput.SelectInput', array(
                'name' => 'per_page',
                'data' => $limits,
                'options' => array(
                    'mobile' => CMS::isModile(),
                    'width' => CMS::isModile() ? 'auto' : 'auto'
                ),
                'htmlOptions' => array(
                    'options' => array($active => array('selected' => true)),
                    'onchange' => 'window.location = $(this).val();',
                    'data-style' => CMS::isModile() ? '' : 'btn-xs'
                )
            ));
            ?>
            <span class="hidden-xs "><?= Yii::t('ShopModule.default', 'товаров'); ?></span>

        </div>


        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">

            <ul id="filter-tabs" class="list-inline pull-right">
                <li class="active"><a class="btn btn-xs <?php if ($itemView === '_grid') echo 'btn-info active'; ?>" href="<?= Yii::app()->request->removeUrlParam('/shop/category/view', 'view') ?>"><i class="icon-grid"></i><span class="hidden"> Сеткой</span></a></li>
                <li><a class="btn btn-xs <?php if ($itemView === '_list') echo 'btn-info active'; ?>" href="<?= Yii::app()->request->addUrlParam('/shop/category/view', array('view' => 'list')) ?>"><i class="icon-menu"></i><span class="hidden"> Списком</span></a></li>
                <li><a class="btn btn-xs <?php if ($itemView === '_table') echo 'btn-info active'; ?>" href="<?= Yii::app()->request->addUrlParam('/shop/category/view', array('view' => 'table')) ?>"><i class="icon-table"></i><span class="hidden"> Таблицей</span></a></li>
            </ul>
        </div>
    </div>
</div>
