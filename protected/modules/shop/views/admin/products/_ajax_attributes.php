<div clsas="container-fluid">
    <?php

    //print_r($model);$_GET['ShopProduct']['type_id']
    //if ($model->type) {
    $chosen = array(); // Array of ids to enable chosen
    $attributes = (isset($model->type->shopAttributes)) ? $model->type->shopAttributes : array();
    $result = array();
    if (empty($attributes))
        Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', 'Список свойств пустой'), false);
    else {
        foreach ($attributes as $a) {
            if ($a->group) {
                $result[$a->group->name][] = $a;
            } else {
                $result['Без группы'][] = $a;
            }

        }
        echo '<div class="row ml-0 mr-0">';
        foreach ($result as $group_name => $attributes) {


            echo '<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4"><h2 class="text-center mt-3">' . $group_name . '</h2>';
            foreach ($attributes as $a) {
                // Repopulate data from POST if exists
                if (isset($_POST['ShopAttribute'][$a->name]))
                    $value = $_POST['ShopAttribute'][$a->name];
                else
                    $value = $model->getEavAttribute($a->name);

                $a->required ? $required = ' <span class="required">*</span>' : $required = null;

                if ($a->type == ShopAttribute::TYPE_DROPDOWN) {
                    $chosen[] = $a->getIdByName();


                    $addOptionLink = ' <div class="input-group-append">';
                    $addOptionLink .= CHtml::link(Html::icon('icon-add'), '#', array(
                        'rel' => $a->id,
                        'data-name' => $a->getIdByName(),
                        'onclick' => 'js: return addNewOption($(this));',
                        'class' => 'btn btn-success float-right0',
                        'title' => Yii::t('ShopModule.admin', 'Создать опцию')
                    ));
                    $addOptionLink .= '</div>';
                } else
                    $addOptionLink = null;
                ?>

                    <?php
                    echo CHtml::openTag('div', array('class' => 'form-group2 row2 clearfix'));
                    echo CHtml::label($a->attr_translate->title . $required, $a->name, array('class' => $a->required ? 'required col-form-label' : 'col-form-label col-sm-42'));
                    echo '<div class="rowInput eavInput input-group">' . $a->renderField($value) . $addOptionLink . '</div>';
                    echo CHtml::closeTag('div');

                    ?>



                <?php

            }
            echo '</div>';

        }
        echo '</div>';
    }
    ?>

</div>

