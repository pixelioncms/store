
<?php
if ($config['filter_enable_price'] && ($this->currentMinPrice > 0 && $this->currentMaxPrice > 0) && ($this->currentMinPrice != $this->currentMaxPrice) //Если у товаров онинаковые цены, false
) {

    ?>
<div class="panel panel-default" id="filter-price">

        <div class="panel-heading">
            <div class="panel-title"><?= Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') ?></div>
        </div>
        <?php echo Html::form() ?>
        <?= Html::hiddenField('min_price', (isset($_GET['min_price'])) ? (int) $this->getCurrentMinPrice() : null, array()) ?>
        <?= Html::hiddenField('max_price', (isset($_GET['max_price'])) ? (int) $this->getCurrentMaxPrice() : null, array()) ?>
        <div class="panel-body">
            <div class="price-range-holder">



                <?php
                $cm = Yii::app()->currency;
                //$getMin = $this->controller->getMinPrice();
                //$getMax = $this->controller->getMaxPrice();
                $getMax = $this->currentMaxPrice;
                $getMin = $this->currentMinPrice;
                $min = (int) floor($getMin); //$cm->convert()
                $max = (int) ceil($getMax);
                //  echo $cm->convert($getMin);

                echo $this->widget('zii.widgets.jui.CJuiSlider', array(
                    'options' => array(
                        'range' => true,
                        'min' => (int) $prices['min'],//$min,
                        'cssFile' => false,
                        'max' => (int) $prices['max'],//$max,
                        'disabled' => (int) $getMin === (int) $getMax,
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
                    <span id="mn"><?= (isset($_GET['min_price'])) ? (int) $this->getCurrentMinPrice() : null ?></span>
                    до   <span id="mx"><?= (isset($_GET['max_price'])) ? (int) $this->getCurrentMaxPrice() : null ?></span>
                    (<?= Yii::app()->currency->active->symbol ?>)</span>
            </div>
            <div class="text-left">
                <br/>
            <button type="submit" class="btn btn-xs btn-danger">OK</button>
</div>
            
        </div>
        <?php echo Html::endForm() ?>
    </div>



<?php } ?>