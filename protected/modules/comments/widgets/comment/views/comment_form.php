<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-create-form',
    'action' => array('/comments/create'), //$currentUrl
    'enableClientValidation' => true,
    'enableAjaxValidation' => true, // Включаем аякс отправку
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
    ),
    'htmlOptions' => array('class' => 'form', 'name' => 'test')
));
?>


<script>
    var comment = {
        foodTime:<?= Yii::app()->settings->get('comments', 'flood_time') ?>,
        foodAlert: true
    };
</script>

<?php echo Html::hiddenField('object_id', $object_id); ?>
<?php echo Html::hiddenField('owner_title', $owner_title); ?>
<?php echo Html::hiddenField('model', $model); ?>


<div class="row1">
    <div class="h5">Ваше имя: <?= Yii::app()->user->username; ?></div>
    <div class="input-group">
        <div class="input-group-append">
            <span class="input-group-text">
                            <?php
                            echo Html::image(Yii::app()->user->getAvatarUrl('50x50'),'');

                            ?>
            </span>



        </div>
        <?php echo $form->textArea($comment, 'text', array('rows' => 4, 'class' => 'form-control')); ?>
    </div>
    <div class="text-right" style="margin-top:15px;">
        <?php
        //echo Html::button(Yii::t('default', 'SEND'), array('class' => 'btn btn-success', 'onclick' => 'fn.comment.add("#comment-create-form");'));
        echo Html::ajaxSubmitButton(Yii::t('default', 'SEND'), array('/comments/create'), array( //$currentUrl
            'type' => 'post',
            'data' => 'js:$("#comment-create-form").serialize()',
            'dataType' => 'json',
            'beforeSend'=>'js:function(){common.addLoader("asdas");}',
            'success' => 'js:function(data) {
            $("#Comments_text").val("");
                if(data.status == "success"){
                    common.notify(data.message,"success");
                    var ft = ' . time() . '+comment.foodTime;
                    $.session.set("caf",ft);
                    $.fn.yiiListView.update("comment-list");
                }else if(data.status == "wait"){
                    var ft = ' . time() . '+comment.foodTime;
                    $.session.set("caf",ft);
                    common.notify(data.message,"info");
                }
                     
            }',
            'error' => 'js:function(jqXHR, textStatus, errorThrown ){
                console.log(jqXHR);
                common.notify(jqXHR,"error");
            }'
        ), array('class' => 'btn btn-success'));
        ?>
    </div>

</div>
<?php $this->endWidget(); ?>

