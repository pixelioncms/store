

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'notify-form',
    'action' => array('/notify/index'),
    'enableAjaxValidation' => false,
    'htmlOptions'=>array('class'=>'from-horizontal')
        ));
?>



<input type="hidden" name="product_id" value="<?= $product->id?>">
<div class="form-group">
    <div class="col-sm-4"><?php echo $form->labelEx($model, 'email') ?></div>
    <div class="col-sm-8"><?php echo $form->textField($model, 'email',array('class'=>'form-control')) ?><?php echo $form->error($model, 'email') ?></div>
</div>


<?php $this->endWidget(); ?>

