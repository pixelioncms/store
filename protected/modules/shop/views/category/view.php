
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5" id="filters">
        
        
        <?php

        $this->widget('mod.shop.widgets.categories.CategoriesWidget', array(
            'htmlOptions' => array('class' => 'nav'),
            'totalCount' => false,
            'itemOptions' => array('class' => 'gagag'),
            'submenuHtmlOptions' => array('class' => '')
        ));
        ?>
        <div id="testf">
        <?php
       /* $this->widget('mod.shop.widgets.filter.FilterWidget', array(
            'model' => $this->model,
            'attributes' => $this->eavAttributes,
            'countAttr' => true,
            'countManufacturer' => true,
        ));*/
        ?>
    </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
        <div class="row">
            <div class="container-fluid">
                <?php
                $this->widget('Breadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?>
                <h1><?php echo Html::encode($this->model->name); ?></h1>
                <div id="shop-sort"><?php $this->renderPartial((Yii::app()->settings->get('shop', 'ajax_mode')) ? '_sorting_ajax' : '_sorting', array('itemView' => $itemView)); ?></div>

                <?php
                if (Yii::app()->settings->get('shop', 'ajax_mode')) {
                    $ajaxUpdate = true;
                    $enableHistory = true;
                } else {
                    $enableHistory = false;
                    $ajaxUpdate = false;
                }

                
  
                
                $this->widget('zii.widgets.CListView', array(//zii.widgets.CListView
                    'id' => 'shop-products',
                    'dataProvider' => $provider,
                    'cssFile'=>false,
                    'ajaxUpdate' => false, //$ajaxUpdate
                   // 'itemsCssClass' => 'items clearfix',
                   // 'template' => '{items} {pager}',
                    //'enableHistory' => true,
                    'itemView' => $itemView,
                   // 'sortableAttributes' => array(
                   //     'name', 'price'
                   // ),
                    /*'pager' => array(
                        'class'=>'LinkPager',
                        'header' => '',
                        'nextPageLabel' => 'Следующая »',
                        'prevPageLabel' => '« Предыдущая',
                        'firstPageLabel' => '«',
                        'lastPageLabel' => '»',
                    )*/
                ));
                ?>
 
               
            </div>
        </div>

    </div> 
</div>









