<?php
if (($this->currentMinPrice >= 0 && $this->currentMaxPrice >= 0) && ($this->currentMinPrice != $this->currentMaxPrice) //Если у товаров онинаковые цены, false
) {

    ?>
    <div class="card" id="filter-price">

        <div class="card-header collapsed" data-toggle="collapse" data-target="#filter<?= md5('price-filter'); ?>">
            <h5><?= Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') ?></h5>
        </div>
        <?php //echo Html::form() ?>

        <div class="card-collapse collapse" id="filter<?= md5('price-filter'); ?>">
            <div class="card-body px-0">
                <div class="price-range-holder">


                    <?php
                    $cm = Yii::app()->currency;
                    //$getMin = $this->controller->getMinPrice();
                    //$getMax = $this->controller->getMaxPrice();
                    $getMax = $this->currentMaxPrice;
                    $getMin = $this->currentMinPrice;
                    $min = (int)floor($getMin); //$cm->convert()
                    $max = (int)ceil($getMax);
                    //  echo $cm->convert($getMin);

                    echo $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options' => array(
                            'range' => true,
                            'min' => (int)$this->getMinPrice(),//$prices['min'],//$min,
                            'cssFile' => false,
                            'max' => (int)$this->getMaxPrice(),//$prices['max'],//$max,
                            'disabled' => (int)$getMin === (int)$getMax,
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
                            <?= Html::textField('min_price', (isset($_GET['min_price'])) ? (int)$this->getCurrentMinPrice() : null, array('class' => 'form-control form-control-sm')) ?>

                        </div>
                        <div class="col text-center align-self-center">до</div>
                        <div class="col text-center">
                            <?= Html::textField('max_price', (isset($_GET['max_price'])) ? (int)$this->getCurrentMaxPrice() : null, array('class' => 'form-control form-control-sm')) ?>

                        </div>
                        <div class="col text-center align-self-center"><?= Yii::app()->currency->active->symbol ?></div>
                    </div>


                    <span id="mn" class="d-none">
                        <?= (isset($_GET['min_price'])) ? (int)$this->getCurrentMinPrice() : null ?>
                    </span>
                    <span id="mx" class="d-none">
                        <?= (isset($_GET['max_price'])) ? (int)$this->getCurrentMaxPrice() : null ?>
                    </span>

                </div>
                <div class="text-left">
                    <br/>
                    <button type="submit" class="btn btn-sm btn-secondary">OK</button>
                </div>

            </div>
            <?php //echo Html::endForm() ?>
        </div>
    </div>


<?php } ?>