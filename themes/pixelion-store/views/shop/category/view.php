<div class="container-fluid catalog-container">
    <div class="catalog-sidebar">

        <?php $this->widget('mod.shop.widgets.categories.CategoriesWidget') ?>


        <?php
        $this->widget('mod.shop.widgets.filter3.FilterWidget3', array(
            'model' => $this->dataModel,
            'attributes' => $this->eavAttributes,
            'countAttr' => true,
            'countManufacturer' => true,
        ));

        ?>


    </div>
    <div class="catalog-content">
        <?php

        //$this->widget('mod.shop.widgets.brands.BrandsWidget');
        ?>
        <h1 class="heading-gradient"><?= $this->pageName; ?></h1>
        <?php $this->renderPartial('_sorting', array('itemView' => $this->itemView)); ?>



        <?php if ($this->itemView == '_view_table') { ?>
        <script>
            $(function () {
                $('.grid-table-row').hover(function () {
                    $(this).toggleClass('active');

                });
            })
        </script>
        <table class="table table-striped">
            <tr>
                <th style="width:5%"></th>
                <th style="width:50%">Наименование</th>
                <th style="width:15%"></th>
                <th style="width:20%">цена</th>
                <th style="width:10%"></th>
            </tr>
            <?php } ?>
            <div id="ajax-grid">
                <?php
                $this->renderPartial('_ajax', array('provider' => $provider, 'itemView' => $this->itemView))
                /* $this->widget('ListView', array(
                     'id' => 'shop-products',
                     'dataProvider' => $provider,
                     'cssFile' => false,
                     'ajaxUpdate' => true, //$ajaxUpdate
                     'enableHistory' => true,
                     'itemsCssClass' => 'items row2 clearfix '.$itemView,
                     //'template' => '<div>{summary}</div>{sorter}{items} {pager}',
                     'template' => '<div>{summary}</div>{items} {pager}',

                     'htmlOptions' => array('class' => ''),
                     'itemView' => $itemView,

                     'sortableAttributes' => array(
                         'name', 'price'
                     ),
                     'afterAjaxUpdate' => 'function(id,data){
                               $("span > input").rating({"readOnly":true});
                               }',
                     'pager' => array(
                         'htmlOptions' => array('class' => 'pagination justify-content-center')
                     )
                      'pager' => array(
                          'class' => 'SuperPager',
                          'enableShowmore' => true,
                          'header' => '',
                          'nextPageLabel' => 'Следующая »',
                          'prevPageLabel' => '« Предыдущая',
                          'firstPageLabel' => '«',
                          'lastPageLabel' => '»',
                      )
                     'pager' => array(
                       'gridid' => 'shop-products',
                       'class' => 'SuperPager',
                       'autoShowMoreLabel' => true,
                       //'showMoreLabel'=>Yii::t('NewsModule.default','PAGER_SHOWMORE'),
                       'showMoreLabel' => CMS::GetFormatWord('ShopModule.default', 'PAGER_SHOWMORE', Yii::app()->cart->countItems()),
                       'lastPageLabel' => false,
                       'nextPageLabel' => false,
                       'prevPageLabel' => false,
                       'firstPageLabel' => false,
                       'header' => '',
                       ),
                       'beforeAjaxUpdate' => "js:function(){
                       $('.showmore-block a').html('Загрузка...').addClass('loading');
                       $.fn.yiiListView('update',{test:1});
                       $('#showmore').click(function(){
                       console.log($(this).text());
                       });

                       }",
                       'afterAjaxUpdate' => "js:function(){


                       }"
                 ));*/
                ?>
            </div>

            <?php if ($this->itemView == '_view_table') { ?>
        </table>
    <?php } ?>

        <?php if (!empty($this->dataModel->description)) { ?>
            <a class="btn btn-link" data-target="#collapse-seo" href="#"
               onClick="$('#collapse-seo').toggleClass('active'); return false;">
                Показать
            </a>
            <div class="container-seo" id="collapse-seo">
                <?= $this->dataModel->description; ?>
            </div>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
</div>