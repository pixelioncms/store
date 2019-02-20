<?php
Yii::app()->getClientScript()->registerScriptFile($this->module->assetsUrl . '/admin/products.variations.js', CClientScript::POS_END);
?>

<div class="variants">
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">Добавить атрибут</label>
        <div class="col-sm-8">
            <div class="input-group">
                <?php
                if ($model->type) {
                    $attributes = $model->type->shopConfigurableAttributes;
                    echo Html::dropDownList('variantAttribute', null, Html::listData($attributes, 'id', 'title'), array('class' => 'form-control'));
                }
                ?>
                <div class="input-group-append">
                    <a href="javascript:void(0)" id="addAttribute"
                       class="btn btn-success"><?= Yii::t('app', 'CREATE', 0) ?></a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>


    <div id="variantsData">
        <?php
        foreach ($model->processVariants() as $row) {
            $this->renderPartial('variants/_table', array(
                'attribute' => $row['attribute'],
                'options' => $row['options']
            ));
        }
        ?>
    </div>
</div>