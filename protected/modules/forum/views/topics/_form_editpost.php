

<?php
if (!Yii::app()->user->isGuest) {




    if (Yii::app()->user->hasFlash('success')) {

        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
    }

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'form-editpost-'.$model->id,
        'action' => array('/forum/topics/editpost', 'id' => $model->id),
        'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => false,
            'validateOnChange' => false,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success',
        ),
        'htmlOptions' => array('class' => 'addreply')
    ));
    ?>


    <div class="form-group">
        <?= $form->labelEx($model, 'text', array('class' => 'label-control')); ?>

        <?php
        $this->widget('mod.forum.components.tinymce.TinymceArea', array(
            'model' => $model,
            'attribute' => 'text',
            'selector'=>'.editor-'.$model->id,
            'htmlOptions'=>array(
                'class'=>'editor-'.$model->id
            )
        ));
        ?>
        <?php //echo $form->textArea($model, 'text', array('class' => 'editor-post')); ?>
        <?= $form->error($model, 'text'); ?>
    </div>
    <div class="form-group">
        <?= $form->labelEx($model, 'edit_reason', array('class' => 'label-control')); ?>
        <?= $form->textField($model, 'edit_reason', array('class' => 'form-control')); ?>
        <?= $form->error($model, 'edit_reason'); ?>
    </div>
    <div class="form-group">

        <a id="btn-post-edit" data-id="<?= $model->id ?>" class=" btn btn-primary btn-upper" href="javascript:void(0)"><?= Yii::t('app', 'SEND') ?></a>
        <?= Html::submitButton('Расширенная форма', array('class' => 'btn btn-default')); ?>
        или
        <?= Html::link('Отмена', 'javascript:void(0);', array('onClick' => 'test()', 'class' => 'btn btn-link remove-editor')); ?>
<?php
echo CHtml::ajaxSubmitButton('Save',array('/forum/topics/editpost', 'id' => $model->id),array(
   'type'=>'POST',
   'dataType'=>'json',
    'data'=>'js:$("#form-editpost-'.$model->id.'").serialize()',
   'success'=>'js:function(data){
      
       $("#post-edit-ajax-" + data.id).html(data.post);
       if(data.post==="success"){
          // do something on success, like redirect
       }else{
         $("#some-container").html(data.msg);
       }
       console.log(data.message);
   }',
    'beforeSend'=>'js:function(){
           tinyMCE.triggerSave();
    }'
));
?>
    </div>
    <?php $this->endWidget(); ?>
<?php } ?>
<script>
    function test() {


        $(".remove-editor").on("click", function () {
            console.log('ss222');
        });


        // tinyMCE.init();
    }
$(function(){
        $("#btn-post-edit").on("click", function () {
        tinyMCE.triggerSave();
        var that = $(this);
        var f = $("#form-editpost-" + that.attr('data-id'));
        var action = f.attr("action");
        var serializedForm = f.serialize();

        $.ajax({
            type: 'POST',
            url: action,
            data: serializedForm,
            dataType: 'json',
            success: function (data, textStatus, request) {
                $("#post-edit-ajax-" + data.id).html(data.post);
                 common.notify(data.message,'success');
            },
            error: function (req, status, error) {
                $("#ajax-addreply").html(data);
            }
        });

        return false;
    });
    
});
</script>
