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
                        <div class="col text-center align-self-center">от</div>
                        <div class="col text-center">
                            <?= Html::textField('min_price', (isset($_GET['min_price'])) ? (int)$this->getOwner()->getCurrentMinPrice() : null, array('class' => 'form-control form-control-sm')) ?>

                        </div>
                        <div class="col text-center align-self-center">до</div>
                        <div class="col text-center">
                            <?= Html::textField('max_price', (isset($_GET['max_price'])) ? (int)$this->getOwner()->getCurrentMaxPrice() : null, array('class' => 'form-control form-control-sm')) ?>

                        </div>
                        <div class="col text-center align-self-center"><?= Yii::app()->currency->active->symbol ?></div>
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
<?php } ?>