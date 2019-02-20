<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array('class' => '')
    ));
    ?>

    <div class="form-group">
        <div class="col-sm-4">
            <?php

            echo Html::activeDropDownList($model,'itemname',$itemnameSelectOptions,array(
                'class' => 'form-control'
            ));
            ?>
        </div>
        <div class="col-sm-8"><?= $form->error($model, 'itemname'); ?></div>
    </div>

    <div class="form-group text-center">
<?php echo Html::submitButton(Rights::t('default', 'Назначать'), array('class' => 'btn btn-success')); ?>
    </div>

<?php $this->endWidget(); ?>

</div>