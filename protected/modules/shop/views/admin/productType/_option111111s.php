<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->getModule('admin')->assetsUrl . '/js/jquery.dualListBox.js', CClientScript::POS_END);
$cs->registerScript('options', "
    $.configureBoxes({useFilters:false,useCounters:false});
", CClientScript::POS_END);
?>

<div class="form-group row">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'name', array('required' => true, 'class' => 'col-form-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'name', array('class' => 'form-control')); ?></div>
</div>
<div class="form-group row">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'attribute_group', array('required' => true, 'class' => 'col-form-label')); ?></div>
    <div class="col-sm-8"><?= Html::activeCheckBox($model, 'attribute_group', array()); ?></div>
</div>

<div class="body form-group row">
    <div class="leftBox col-lg-5">

        <?= Html::label(Yii::t('ShopModule.admin', 'Атрибуты продукта'), 'box2View') ?>
        <br/>
        <?php /*/echo Html::dropDownList('attributes[]', null, Html::listData($model->shopAttributes, 'id', 'title', function (ShopAttribute $attribute) {
            return ($attribute->group) ? $attribute->group->name : Yii::t('ShopModule.admin','NO_GROUP');
        }), array('id' => 'box2View', 'multiple' => true, 'class' => 'form-control multiple attributesList', 'style' => 'height:300px;'));*/
        ?>

        <br/>
        <span id="box2Counter" class="countLabel"></span>
    </div>

    <div class="dualControl col-lg-2 text-center" style="margin-top:40px">
        <div class="btn-group">
            <button id="to2" type="button" class="dualBtn btn btn-secondary"><i class="icon-arrow-left"></i></button>
            <button id="to1" type="button" class="dualBtn btn btn-secondary"><i class="icon-arrow-right"></i></button>
        </div>
        <br/>
        <br/>
        <div class="btn-group">
            <button id="allTo2" type="button" class="dualBtn btn btn-secondary"><i class="icon-double-arrow-left"></i>
            </button>
            <button id="allTo1" type="button" class="dualBtn btn btn-secondary"><i class="icon-double-arrow-right"></i>
            </button>
        </div>
    </div>

    <div class="rightBox col-lg-5">

        <?= Html::label(Yii::t('ShopModule.admin', 'Доступные атрибуты'), 'box1View') ?><br/>
        <?= Html::dropDownList('allAttributes', null, Html::listData($attributes, 'id', 'title', function (ShopAttribute $attribute) {
            return ($attribute->group) ? $attribute->group->name : Yii::t('ShopModule.admin','NO_GROUP');
        }), array('id' => 'box1View', 'multiple' => true, 'class' => 'form-control multiple attributesList', 'style' => 'height:300px;'));
        ?>

        <br/>
        <span id="box1Counter" class="countLabel"></span>

    </div>
</div>
<?php

$active = array();
foreach ($model->shopAttributes as $curr) {

    $active[] = $curr->id;
}

?>
<?= Html::dropDownList('attributes2[]', $active, Html::listData($attributes, 'id', 'title', function (ShopAttribute $attribute) {
    return ($attribute->group) ? $attribute->group->name : 'No Group';
}), array(
    'multiple' => true,
    'class' => 'form-control multiple',
    'style' => 'min-height:300px;'
));
?>

