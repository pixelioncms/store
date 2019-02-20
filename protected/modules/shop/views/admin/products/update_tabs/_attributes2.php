

<?php

//print_r($model);$_GET['ShopProduct']['type_id']
//if ($model->type) {
$chosen = array(); // Array of ids to enable chosen
$attributes = (isset($model->type->shopAttributes))?$model->type->shopAttributes:array();

if (empty($attributes))
    Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', 'Список свойств пустой'), false);
else {
    foreach ($attributes as $a) {
        echo $a->group->name;
        // Repopulate data from POST if exists
        if (isset($_POST['ShopAttribute'][$a->name]))
            $value = $_POST['ShopAttribute'][$a->name];
        else
            $value = $model->getEavAttribute($a->name);

        $a->required ? $required = ' <span class="required">*</span>' : $required = null;

        if ($a->type == ShopAttribute::TYPE_DROPDOWN) {
            $chosen[] = $a->getIdByName();


            $addOptionLink =' <div class="input-group-append">';
            $addOptionLink .= CHtml::link(Html::icon('icon-add'), '#', array(
                        'rel' => $a->id,
                        'data-name' => $a->getIdByName(),
                        'onclick' => 'js: return addNewOption($(this));',
                        'class' => 'btn btn-success float-right0',
                        'title' => Yii::t('ShopModule.admin', 'Создать опцию')
            ));
            $addOptionLink .='</div>';
        } else
            $addOptionLink = null;

        echo CHtml::openTag('div', array('class' => 'form-group row clearfix'));
        echo CHtml::label($a->attr_translate->title . $required, $a->name, array('class' => $a->required ? 'required col-form-label col-sm-4' : 'col-form-label col-sm-4'));
        echo '<div class="col-sm-8 rowInput eavInput input-group">' . $a->renderField($value)  . $addOptionLink.'</div>';
        echo CHtml::closeTag('div');

    }
    //   }
}