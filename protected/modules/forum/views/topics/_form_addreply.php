<?php
if (!Yii::app()->user->isGuest) {




    if (Yii::app()->user->hasFlash('success')) {

        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
    }
    $postModel = new ForumPosts;

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'addreply',
        'action' => array('/forum/topic/addreply'),
        'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success',
        ),
        'htmlOptions' => array('class' => 'addreply')
    ));

    ?>
    <?= $form->hiddenField($postModel, 'topic_id', array('value' => $model->id)); ?>
    <?= $form->hiddenField($postModel, 'user_id', array('value' => Yii::app()->user->id)); ?>

    <div class="form-group">
        <?= $form->labelEx($postModel, 'text', array('class' => 'label-control')); ?>

        <?php
        $this->widget('mod.forum.components.tinymce.TinymceArea', array(
            'model' => $postModel,
            'attribute' => 'text',
                 'selector'=>'#ForumPosts_text'
        ));
        ?>
        <?php //echo $form->textArea($postModel, 'text', array('class' => 'form-control')); ?>
        <?= $form->error($postModel, 'text'); ?>
    </div>
    <div class="form-group">

        <a id="add-post-reply" class=" btn btn-primary btn-upper" href="javascript:void(0)"><?= Yii::t('app', 'SEND') ?></a>
        <?= Html::submitButton('Расширенная форма', array('class' => 'btn btn-default')); ?>

    </div>
    <?php $this->endWidget(); ?>
<?php } ?>

<?php
Yii::app()->clientScript->registerScript('add-post-reply', "
        $('#add-post-reply').on('click', function () {
            tinyMCE.triggerSave();
            var f = $('#addreply');
            var action = f.attr('action');
            var serializedForm = f.serialize();
            serializedForm +='&json=1';
            //tinyMCE.triggerSave();
            $.ajax({
                type: 'POST',
                url: action,
                data: serializedForm,
                dataType:'json',
                //async: false,
                success: function (data, textStatus, request) {
                    //$('#ajax-addreply').html(data);
                    common.notify(data.message,'success');
                    $.fn.yiiListView.update('topic-list');
                },
                error: function (req, status, error) {
                    $('#ajax-addreply').html(data);
                }
            });
            return false;
        });
", CClientScript::POS_END);
?>
