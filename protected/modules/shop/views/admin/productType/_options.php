<div class="form-group row">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'name', array('required' => true, 'class' => 'col-form-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'name', array('class' => 'form-control')); ?></div>
</div>
<div class="form-group row">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'attribute_group', array('required' => true, 'class' => 'col-form-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeCheckBox($model, 'attribute_group', array()); ?></div>
</div>

<div class="form-group row">
    <div class="col-sm-4"><?= Html::label(Yii::t('ShopModule.admin', 'Атрибуты продукта'), 'att',array('class' => 'col-form-label')) ?></div>
    <div class="col-sm-8">
        <?php

        $active = array();
        foreach ($model->shopAttributes as $curr) {
            $active[] = $curr->id;
        }

        ?>
        <?= Html::dropDownList('attributes2[]', $active, Html::listData($attributes, 'id', 'title', function (ShopAttribute $attribute) {
            return ($attribute->group) ? $attribute->group->name : Yii::t('ShopModule.admin','NO_GROUP');
        }), array(
            'id'=>'att',
            'multiple' => true,
            'class' => 'form-control multiple',
            'style' => 'min-height:300px;'
        ));
        ?>
    </div>


</div>


