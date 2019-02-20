<script>
    /*$(function () {
     $(document).on('change', '#sorter', function (e) {
     var uri = $(this).val();
     var ss = categoryFullUrl;
     uri.replace(new RegExp(ss,'g'),"");
     //  str.replace(new RegExp('//','g'),"")
     console.log(uri);
     console.log($(this).val());
     $.fn.yiiListView.update('shop-products', {
     url: $(this).val(),
     data: {sort:'price.desc'}
     });



     history.pushState({}, $('title').val(), $(this).val());
     return false;
     });

     $(document).on('change', '#per_page', function (e) {
     var uri = $(this).val();
     var ss = categoryFullUrl;
     uri.replace(new RegExp(ss,'g'),"");
     //  str.replace(new RegExp('//','g'),"")
     console.log(uri);
     console.log($(this).val());
     $.fn.yiiListView.update('shop-products', {
     url: $(this).val(),
     data: {per_page:'price.desc'}
     });



     history.pushState({}, $('title').val(), $(this).val());
     return false;
     });
     });*/
</script>
<div class="row">
<div class="clearfix col shop-sorter">
    <div class="row">


        <div class="col-sm-5 col-md-5 col-lg-5">

            <span class="hidden-md hidden-sm"><?= Yii::t('ShopModule.default', 'VIEW'); ?> </span>
            <?php
            $sorter[Yii::app()->request->removeUrlParam('/shop/category/view', 'sort')] = Yii::t('ShopModule.default', 'SORT_BY', 0);
            $sorter[Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'price'))] = Yii::t('ShopModule.default', 'SORT_BY', 1);
            $sorter[Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'price.desc'))] = Yii::t('ShopModule.default', 'SORT_BY', 2);
            $sorter[Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => 'date_create.desc'))] = Yii::t('ShopModule.default', 'SORT_BY', 3);
            $active = Yii::app()->request->addUrlParam('/shop/category/view', array('sort' => Yii::app()->request->getParam('sort')));


            echo Html::dropDownList('sorter', $active, $sorter, array(
                //  'options' => array($active => array('selected' => true)),
                'onchange' => 'window.location = $(this).val();',
                'class' => 'custom-select',
                'style' => 'width:auto'
            ))

            ?>


        </div><!-- /.col -->
        <div class="col-sm-3 col-md-4 col-lg-4">


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
            echo Html::dropDownList('per_page', $active, $limits, array(
                //  'options' => array($active => array('selected' => true)),
                'onchange' => 'window.location = $(this).val();',
                'class' => 'custom-select',
                'style' => 'width:auto'
            ));


            ?>
            <span class="hidden-xs "><?= Yii::t('ShopModule.default', 'товаров'); ?></span>

        </div>


        <div class="col-sm-4 col-md-3 col-lg-3">


            <div class="btn-group btn-group-sm d-flex justify-content-end">
                <a class="btn btn-info <?php if ($itemView === '_view_grid') echo 'active'; ?>"
                   href="<?= Yii::app()->request->removeUrlParam('/shop/category/view', 'view') ?>"><i
                            class="icon-grid"></i><span class="d-none"> Сеткой</span></a>
                <a class="btn btn-info <?php if ($itemView === '_view_list') echo 'active'; ?>"
                   href="<?= Yii::app()->request->addUrlParam('/shop/category/view', array('view' => 'list')) ?>"><i
                            class="icon-menu"></i><span class="d-none"> Списком</span></a>
                <a class="btn btn-info <?php if ($itemView === '_view_table') echo 'active'; ?>"
                   href="<?= Yii::app()->request->addUrlParam('/shop/category/view', array('view' => 'table')) ?>"><i
                            class="icon-table"></i><span class="d-none"> Таблицей</span></a>

            </div>

        </div>
    </div>
</div>
</div>