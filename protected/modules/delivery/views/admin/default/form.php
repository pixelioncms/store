<script>

    function send(formid, reload){
        var str = $(formid).serialize();
        $.ajax({
            url: $(formid).attr('action'),
            type: 'POST',
            data: str,
            success: function(data){
                $(reload).html(data);
            },
            complete: function(){

            } 
        });
            
            
        
    }

</script>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'id' => 'div-form',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
    'htmlOptions' => array('action' => '/delivery/admin', 'name' => 'div-form','class'=>'')
        ));
?> 
<?php

$countUsers = count($users);
$countDelivery = count($delivery);
$countAll = count(CMap::mergeArray($users, $delivery));
//$countAll = count(array_unique(CMap::mergeArray($users, $delivery)));
?>
<div class="form-group row">
    <?php echo $form->labelEx($model, 'themename',array('class'=>'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8"><?php echo $form->textField($model, 'themename',array('class'=>'form-control')); ?><?php echo $form->error($model, 'themename'); ?></div>
</div>
<div class="form-group row">
    <?php echo $form->labelEx($model, 'text',array('class'=>'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8"><?php echo $form->textArea($model, 'text',array('class'=>'form-control')); ?><?php echo $form->error($model, 'text'); ?></div>
</div>
<div class="form-group row">
    <?php echo $form->labelEx($model, 'from',array('class'=>'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8 noSearch"><?php echo $form->dropDownList($model, 'from', array('all' => 'Всем (' . $countAll . ')', 'users' => 'Пользователям (' . $countUsers . ')', 'delivery' => 'Подписчикам (' . $countDelivery . ')'),array('class'=>'select form-control')); ?><?php echo $form->error($model, 'from'); ?></div>
</div>
<div class="form-group row">

    <div class="col text-center"><a href="javascript:void(0)" class="btn btn-success" onclick="send('#div-form','#response-box')">Начать отправку!</a></div>
</div>


<?php $this->endWidget(); ?>


