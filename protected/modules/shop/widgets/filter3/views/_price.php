<?php

$cm = Yii::app()->currency;
$getDefaultMin = (int)floor($this->controller->getMinPrice());
$getDefaultMax = (int)ceil($this->controller->getMaxPrice());
$getMax = $this->getOwner()->currentMaxPrice;
$getMin = $this->getOwner()->currentMinPrice;
$min = (int)floor($getMin); //$cm->convert()
$max = (int)ceil($getMax);

if ($getDefaultMin !== $getDefaultMax) { //Если у товаров онинаковые цены, false

    ?>
    <div class="card" id="filter-price">

        <div class="card-header collapsed" data-toggle="collapse" data-target="#filter<?= md5('price-filter'); ?>">
            <h5><?= Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') ?></h5>
        </div>

        <div class="card-collapse collapse" id="filter<?= md5('price-filter'); ?>">
            <div class="card-body px-0">
                <div class="price-range-holder">


                    <?php


                    echo $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options' => array(
                            'range' => true,
                            'min' => $getDefaultMin,//$prices['min'],//$min,
                            'cssFile' => false,
                            'max' => $getDefaultMax,//$prices['max'],//$max,
                            'disabled' => $getDefaultMin === $getDefaultMax,
                            'values' => array($getMin, $getMax),
                            'slide' => 'js:function(event, ui) {
                                $("#min_price").val(ui.values[0]);
                                $("#max_price").val(ui.values[1]);
                                $("#mn").text(price_format(ui.values[0]));
                                $("#mx").text(price_format(ui.values[1]));
                                

			                }',
                            'stop'=>'js:function(event, ui) {
                                var filterData = $("#filter-form").serializeObject();
                                var uri = categoryFullUrl;
                                delete filterData.token;
                                
                                $.each(filterData,function(name,values) {
                                    var matches = name.match(/filter\[([a-zA-Z0-9-_]+)\]\[]/i);
                                    uri += (matches) ? "/"+matches[1] : "/"+name;
                    
                                    if(values instanceof Array){
                                        uri += "/"+values.join(",");
                                    }else{
                                        uri += "/"+values;
                                    }
                                });
                    
                                $.fn.yiiListView.update("shop-products",{url: "/"+uri});
                                
                                if(xhrCurrentFilter && xhrCurrentFilter.readyState != 4){
                                    xhrCurrentFilter.onreadystatechange = null;
                                    xhrCurrentFilter.abort();
                                }
                                
                                xhrCurrentFilter = $.ajax({
                                    type:"GET",
                                    url:"/"+uri,
                                    beforeSend:function(){
                                        $("#ajax_filter_current").addClass("loading");
                                    },
                                    success:function(data){
                                        $("#ajax_filter_current").html(data).removeClass("loading");
                                    }
                                });
                                
                                history.pushState(null, false, "/"+uri);
                                
                            }',
                            'create' => 'js:function(event, ui){
                                $("#min_price").val(' . $min . ');
                                $("#max_price").val(' . $max . ');
                                $("#mn").text("' . Yii::app()->currency->number_format($min) . '");
                                $("#mx").text("' . Yii::app()->currency->number_format($max) . '");
                            }'
                        ),
                        'htmlOptions' => array('class' => 'price-slider'),
                    ), true);
                    ?>


                    <div class="filter-slider-price row no-gutters">
                        <div class="col-sm-12 col-md-12 col-lg-6 text-left">
                            <span class=" align-self-center">от</span>
                            <?= Html::textField('min_price', (isset($_GET['min_price'])) ? (int)$this->getOwner()->getCurrentMinPrice() : null, array('class' => 'form-control form-control-sm')) ?>
                            <span class="align-self-center d-inline-block"><?= Yii::app()->currency->active->symbol ?></span>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 text-left">
                            <span class="align-self-center">до</span>
                            <?= Html::textField('max_price', (isset($_GET['max_price'])) ? (int)$this->getOwner()->getCurrentMaxPrice() : null, array('class' => 'form-control form-control-sm')) ?>
                            <span class="align-self-center d-inline-block"><?= Yii::app()->currency->active->symbol ?></span>
                        </div>


                        <span id="mn" class="d-none">
                        <?= (isset($_GET['min_price'])) ? (int)$this->getOwner()->getCurrentMinPrice() : null ?>
                    </span>
                        <span id="mx" class="d-none">
                        <?= (isset($_GET['max_price'])) ? (int)$this->getOwner()->getCurrentMaxPrice() : null ?>
                    </span>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>