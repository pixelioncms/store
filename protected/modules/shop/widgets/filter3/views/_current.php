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
Yii::app()->clientScript->registerScript('filter-current', "
    $(function () {
        var xhrCurrentFilter;
        $('#filter-current a').on('click',function(e){
            e.preventDefault();
            var uri = $(this).attr('href');
            var target = $(this).data('target');
            $(target).click();
            //if(xhrCurrentFilter && xhrCurrentFilter.readyState != 4){
            //    xhrCurrentFilter.onreadystatechange = null;
            //    xhrCurrentFilter.abort();
            //}
            
            //$.fn.yiiListView.update('shop-products',{url: uri});
            
            
            /*xhrCurrentFilter = $.ajax({
                type:'GET',
                url:uri,
                success:function(data){
                    $('#ajax_filter_current').html(data);
                    history.pushState(null, false, uri);
                }
            });*/
        });
        
        
        $('#filter-current a.btn').on('click',function(e){
            e.preventDefault();
            var uri = $(this).attr('href');
            var target = $(this).data('target');
            
            $('#filters input[type=\"checkbox\"]').prop('checked',false);
            
            if(xhrCurrentFilter && xhrCurrentFilter.readyState != 4){
                xhrCurrentFilter.onreadystatechange = null;
                xhrCurrentFilter.abort();
            }
            
            $.fn.yiiListView.update('shop-products',{url: uri});
            
            
            xhrCurrentFilter = $.ajax({
                type:'GET',
                url:uri,
                beforeSend:function(){
                    $('#ajax_filter_current').addClass('loading');
                },
                success:function(data){
                    $('#ajax_filter_current').html(data).removeClass('loading');
                    history.pushState(null, false, uri);
                }
            });
        });
    });
", CClientScript::POS_END);