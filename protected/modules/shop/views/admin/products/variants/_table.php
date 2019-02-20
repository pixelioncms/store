

<table class="variantsTable table table-bordered" id="variantAttribute<?php echo $attribute->id ?>">
    <thead>
        <tr>
            <td colspan="6">
                <h4><?php echo Html::encode($attribute->title); ?></h4>
                <?php
                echo Html::link(Yii::t('ShopModule.admin','ADD_OPTION'), '#', array(
                    'rel' => $attribute->id,
                    'class' => 'btn btn-sm btn-success',
                    'onclick' => 'js: return addNewOption($(this));',
                    'data-name' => $attribute->getIdByName(),
                ));
                ?>
            </td>
        </tr>
        <tr>
            <th>Значение</th>
            <th><?=Yii::t('ShopModule.ShopProduct','PRICE')?> (<?=Yii::app()->currency->main->iso?>)</th>
            <th>Тип цены</th>
            <th><?=Yii::t('ShopModule.ShopProduct','SKU')?></th>
            <th class="text-center">
                <?php
                echo Html::link('<i class="icon-add"></i>', '#', array(
                    'rel' => '#variantAttribute' . $attribute->id,
                    'class' => 'plusOne btn btn-sm btn-success',
                    'onclick' => 'js: return cloneVariantRow($(this));'
                ));
                ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!isset($options)) { ?>
            <tr>
                <td>
                    <?php echo Html::dropDownList('variants[' . $attribute->id . '][option_id][]', null, CHtml::listData($attribute->options, 'id', 'value'), array('class' => 'options_list select form-control')); ?>
                </td>
                <td>
                    <input class="form-control" type="text" name="variants[<?php echo $attribute->id ?>][price][]">
                </td>
                <td>
                    <?= Html::dropDownList('variants[' . $attribute->id . '][price_type][]', null, array(0 => Yii::t('ShopModule.admin','VARIANTS_PRICE_TYPE',0), 1 => Yii::t('ShopModule.admin','VARIANTS_PRICE_TYPE',1)), array('class' => 'form-control')); ?>
                </td>
                <td>
                    <input class="form-control" type="text" name="variants[<?php echo $attribute->id ?>][sku][]" />
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="return deleteVariantRow($(this));"><i class="icon-delete"></i></a>
                </td>
            </tr>
        <?php } ?>
        <?php
        if (isset($options)) {
            foreach ($options as $o) {
                ?>
                <tr>
                    <td>
                        <?php echo Html::dropDownList('variants[' . $attribute->id . '][option_id][]', $o->option->id, CHtml::listData($attribute->options, 'id', 'value'), array('class' => 'options_list form-control')); ?>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="variants[<?php echo $attribute->id ?>][price][]" value="<?php echo $o->price ?>">
                    </td>
                    <td>
                        <?php echo CHtml::dropDownList('variants[' . $attribute->id . '][price_type][]', $o->price_type, array(0 => Yii::t('ShopModule.admin','VARIANTS_PRICE_TYPE',0), 1 => Yii::t('ShopModule.admin','VARIANTS_PRICE_TYPE',1)), array('class' => 'form-control')); ?>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="variants[<?php echo $attribute->id ?>][sku][]" value="<?php echo $o->sku ?>">
                    </td>
                    <td class="text-center">
                        <a href="javascript:void()" class="btn btn-sm btn-danger" onclick="return deleteVariantRow($(this));"><i class="icon-delete"></i></a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>