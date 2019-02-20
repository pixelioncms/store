
<?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array(''))); ?>
<div class="form-group row">
    <div class="col-sm-12">
        <?php
        /*echo Html::activeDropDownList($model,'itemname',$itemnameSelectOptions,array(
            'multiple' => 'multiple',
            'style'=>'height:500px;',
            'class' => 'form-control'
        ));*/

        $this->widget('ext.bootstrap.selectinput.SelectInput',array(
                'model'=>$model,
            'attribute'=>'itemname',
            'data'=>$itemnameSelectOptions,
            'options'=>array(
                'liveSearch'=>true,


            ),
            'htmlOptions'=>array(
                'multiple' => 'multiple',

            )

        ));
        ?>

        <?php echo $form->error($model, 'itemname'); ?>
    </div>
</div>
<div class="form-group row text-center">
    <div class="col">
    <?php echo Html::submitButton(Yii::t('app', 'CREATE', 0), array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

