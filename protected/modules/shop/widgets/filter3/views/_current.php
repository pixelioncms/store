<?php
$active = $this->getOwner()->getActiveFilters();

if (!empty($active)) {
    ?>
    <div class="card" id="filter-current">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#filter<?= md5('current-filter'); ?>">
            <h5><?= Yii::t('ShopModule.default', 'CURRENT_FILTER_TITLE') ?></h5>
        </div>
        <div class="card-collapse collapse2" id="filter<?= md5('current-filter'); ?>">
            <div class="card-body">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'htmlOptions' => array('class' => 'current-filter-list'),
                    'items' => $active
                ));
                ?>
                <div class="text-center">
                    <?= Html::link(Yii::t('ShopModule.default', 'RESET_FILTERS_BTN'), $this->getOwner()->dataModel->getUrl(), array('class' => 'btn btn-sm btn-outline-secondary')); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php
Yii::app()->clientScript->registerScript('current-filter', "
    $('#filter-current li a').on('click', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var target = $(this).data('target');

        if (target === undefined) {
            //if($(this).parent().parent().find('li').length === 1){
            //    $(this).parent().parent().parent().remove();
            //}
            $(this).parent().remove();
            $.fn.yiiListView.update('shop-products', {url: url});
            currentFilters(url);
            history.pushState(null, $('title').text(), url);

        } else {
            if(!$('#current-filter-prices ul').length){
                flagDeletePrices=true;
            }

            $(target).click();
        }
    });
    
    
    //Button reset all filters
    $('#filter-current a.btn').on('click', function (e) {
        e.preventDefault();
        var uri = $(this).attr('href');
        var target = $(this).data('target');

        $('#filters input[type=\"checkbox\"]').prop('checked', false);



        var slider = $('#filter-price-slider');
        var min = slider.slider('option', 'min');
        var max = slider.slider('option', 'max');
        $('#min_price').val(min);
        $('#max_price').val(max);

        slider.slider('values', [min, max]);


        $.fn.yiiListView.update('shop-products', {url: uri});

        currentFilters(uri);
        history.pushState(null, $('title').text(), uri);
    });
", CClientScript::POS_END);