<div class="form-group row field_price">
    <?= Html::activeLabelEx($model, 'price', array('class' => 'col-sm-3 col-md-3 col-lg-2 col-form-label')); ?>
    <div class="col-sm-9 col-md-9 col-lg-10">
        <div class="input-group">
            <div class="row">
                <div class="col-md-4">
                    <?= Html::activeTextField($model, 'price', array('class' => 'form-control w-100 mb-3')); ?>
                </div>
                <div class="col-md-4">
                    <?= Html::activeDropDownList($model, 'currency_id', Html::listData(ShopCurrency::model()->findAllByAttributes(array('is_default'=> 0)), 'id', function($data) {
                        return CHtml::encode($data->name) .' ('.CHtml::encode($data->symbol).')';
                    }), array('class' => 'form-control w-100 mb-3', 'empty' => Yii::app()->currency->main->name.' ('. Yii::app()->currency->main->symbol.')')); ?>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">за</span>
                        </div>
                        <?= Html::activeDropDownList($model, 'unit', $model->getUnits(), array('class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
        </div>
        <a id="add-price" class="text-success" href="#"><i class="icon-add"></i> <?= $model::t('ADD_WHOLESALE_PRICE'); ?></a>
    </div>


    <div class="col" id="extra-prices">
        <?php foreach ($model->prices as $price) { ?>

            <div id="price-row-<?= $price->id ?>">
                <hr/>
                <div class="row">
                    <?php echo Html::label('Цена', 'ShopProductPrices_' . $price->id . '_value', array('class' => 'col-sm-3 col-md-3 col-lg-2 col-form-label', 'required' => true)); ?>
                    <div class="col-sm-9 col-md-6 col-lg-5 col-xl-3">
                        <div class="input-group mb-2">
                            <?php echo Html::textField('ShopProductPrices[' . $price->id . '][value]', $price->value, array('class' => 'float-left form-control')); ?>
                            <div class="input-group-append">
                                    <span class="col-form-label ml-3">
                                        <span class="currency-name">грн.</span> за
                                        <span class="unit-name">шт.</span>
                                        <a href="#" data-price-id="<?= $price->id ?>"
                                           class="remove-price btn btn-sm btn-danger">
                                            <i class="icon-delete"></i>
                                        </a>
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <?php echo Html::label('При заказе от', 'ShopProductPrices_' . $price->id . '_order_from', array('class' => 'col-sm-3 col-md-3 col-lg-2 col-form-label', 'required' => true)); ?>
                    <div class="col-sm-9 col-md-6 col-lg-5 col-xl-3">
                        <div class="input-group mb-3 mb-sm-0">
                            <?php echo Html::textField('ShopProductPrices[' . $price->id . '][order_from]', $price->order_from, array('class' => 'float-left form-control')); ?>
                            <div class="input-group-append">
                                <span class="col-form-label ml-3 unit-name">шт.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
