<?php
if ($config->filter_enable_price && ($this->currentMinPrice >= 0 && $this->currentMaxPrice >= 0) && ($this->currentMinPrice != $this->currentMaxPrice) //Если у товаров онинаковые цены, false
) {

    ?>
    <div class="card" id="filter-price">

        <div class="card-header collapsed" data-toggle="collapse" data-target="#filter<?= md5('price-filter'); ?>">
            <h5><?= Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') ?></h5>
        </div>
        <?php echo Html::form() ?>
        <?= Html::hiddenField('min_price', (isset($_GET['min_price'])) ? (int)$this->getCurrentMinPrice() : null, array()) ?>
        <?= Html::hiddenField('max_price', (isset($_GET['max_price'])) ? (int)$this->getCurrentMaxPrice() : null, array()) ?>
        <div class="card-collapse collapse" id="filter<?= md5('price-filter'); ?>">
            <div class="card-body">
                <div class="price-range-holder">


                    <?php
                    $cm = Yii::app()->currency;
                    $getDefaultMin = (int)floor($this->controller->getMinPrice());
                    $getDefaultMax = (int)ceil($this->controller->getMaxPrice());
                    $getMax = $this->currentMaxPrice;
                    $getMin = $this->currentMinPrice;
                    $min = (int)floor($getMin); //$cm->convert()
                    $max = (int)ceil($getMax);
                    //  echo $cm->convert($getMin);

                    echo $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options' => array(
                            'range' => true,
                            //'step'=>5,
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


                    <span class="min-max">
                    Цена от  
                    <span id="mn"><?= (isset($_GET['min_price'])) ? (int)$this->getCurrentMinPrice() : null ?></span>
                    до   <span id="mx"><?= (isset($_GET['max_price'])) ? (int)$this->getCurrentMaxPrice() : null ?></span>
                    (<?= Yii::app()->currency->active->symbol ?>)</span>
                </div>
                <div class="text-left">
                    <br/>
                    <button type="submit" class="btn btn-xs btn-danger">OK</button>
                </div>

            </div>
            <?php echo Html::endForm() ?>
        </div>
    </div>


<?php } ?>