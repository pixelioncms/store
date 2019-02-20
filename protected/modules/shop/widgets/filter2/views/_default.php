<?php
$config = Yii::app()->settings->get('shop');
if(!Yii::app()->request->isAjaxRequest){
    $path='';
}
/**
 * @var $this FilterWidget
 */
/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 * 	    'title'=>'Filter Title',
 * 	    'selectMany'=>true, // Can user select many filter options
 * 	    'filters'=>array(array(
 * 	        'title'      => 'Title',
 * 	        'count'      => 'Products count',
 * 	        'queryKey'   => '$_GET param',
 * 	        'queryParam' => 'many',
 * 	    ))
 *  );
 */
// Render active filters
Yii::import('mod.shop.widgets.filter.actions.FilterHelper');

echo Html::openTag('div', array('id' => 'filters'));
// Currency selected filters
$helper = new FilterHelper($this->getOwner());
$active = $helper->getActiveFilters();
if(Yii::app()->request->isAjaxRequest){
    print_r($this->getOwner());die;
    print_r($active);
}
if (!empty($active)) {
    ?>
    <div class="card" id="filter-current">

        <h5 class="card-header">
            <h5><?= Yii::t('ShopModule.default', 'CURRENT_FILTER_TITLE') ?></h5>
        </div>
        <div class="card-body">
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'htmlOptions' => array('class' => 'current-filter-list'),
                'items' => $active
            ));
            echo Html::link(Yii::t('ShopModule.default', 'RESET_FILTERS_BTN'), $this->getOwner()->model->getUrl(), array('class' => 'btn btn-xs btn-default'));
            ?>
        </div>

    </div>
<?php }


// Filter by prices
if (!empty($manufacturers['filters']) || !empty($attributes))
    echo $this->render('_price', array('config' => $config, 'prices' => $prices), true);

// Filter by manufacturer
if (!empty($manufacturers['filters']) || !empty($attributes))
    echo $this->render('_manufacturer', array(
        'config' => $config,
        'manufacturers' => $manufacturers,
        'attributes' => $attributes
            ), true);

// Filters by attributes

echo $this->render("{$path}_attributes", array(
    'config' => $config,
    'attributes' => $attributes
        ), true);


echo Html::closeTag('div');
?>
<script>
    $(function () {
        $(document).on('click', '.filter-list > li > a', function (e) {
            console.log($(this).attr('href'));
            $.fn.yiiListView.update('shop-products',
                    //{data: $(this).attr('href')}
                            {url: $(this).attr('href')}
                    );
               

                    $.ajax({
                        url: '/shop/ajax/filter',
                       // url: '/shop/ajax/filter',
                        type: 'GET',
                        data:{tip:18,seo_alias:'test'},
                        beforeSend:function(){
                            $('#testf').addClass('loading');
                        },
                        success: function (data) {
                            //alert('success!');
                            $('#testf').html(data).removeClass('loading');
                            $(this).toggleClass('active');
                        }
                    });
                    return false;
                });


    });
</script>