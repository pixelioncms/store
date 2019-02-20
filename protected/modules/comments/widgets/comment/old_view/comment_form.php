


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

<style>


    #comment-form{
        margin: 0 auto;
        width:560px;
    }
    #comment-form h3{
        margin-bottom: 20px;
    }
    #comment-form .comment-form-author{
        width: 100px;
    }
    #comment-form .comment-form-author-image{
        margin-top: 20px;
    }
    #comment-form .comment-form-text{
        width:460px;

    }
    #comment-form .comment-form-text textarea{
        width:370px;
    }
    #comment-form .comment-form-text .comment-form-buttons{
        margin-top: 10px;
    }
    .input-row{
        margin: 0 0 15px 0;
    }
    .input-row input{
        margin-top: 5px;
    }
</style>


<script>
var comment = {
    foodTime:<?= Yii::app()->settings->get('comments','flood_time')?>,
    foodAlert:true
};
</script>


<div id="comment-form" class="formRow noBorderB">

    <div class="comment-form-author floatL">
        <div class="comment-form-author-image">
            <?php echo CHtml::image(Yii::app()->user->avatarPath); ?>
        </div>
    </div>
    <div class="comment-form-text floatR">


        <?php //echo $form->error($comment, 'text');  ?>
        <?php echo $form->textArea($comment, 'text'); ?>
        <div class="comment-form-buttons float-r">
            <?php
             echo Html::button(Yii::t('default', 'SEND'), array('class' => 'button btn-green', 'onclick' => 'fn.comment.add("#comment-create-form");'));
            echo CHtml::ajaxSubmitButton(Yii::t('default', 'SEND'), $currentUrl, array(
                'type' => 'post',
                'data' => 'js:$("#comment-create-form").serialize()',
              //  'dataType'=>'json',
                'success' => 'js:function(data) {

                    var ft = '.time().'+comment.foodTime;
                    $.session.set("caf",ft);
                  $.fn.yiiListView.update("comment-list");

                     
                    }',
                'error' => 'js:function(jqXHR, textStatus, errorThrown ){
       console.log(jqXHR);
                }'
                    ), array('id' => 'mybtn', 'class' => 'buttonS bGreen'));
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>

<?php $this->endWidget(); ?>

