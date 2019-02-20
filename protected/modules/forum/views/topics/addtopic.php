<h2><?= Yii::t('ForumModule.default', 'TITLE_ADD_TOPIC', array('{name}' => $category->name)) ?></h2>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'addtopic-form',
    //'action' => array('/forum/addcat/1'),
    'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnType' => true,
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => 'addtopic')
        ));
?>
<div class="row">
    <div class="col-md-9">


        <?php
        echo $form->errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>
        <div class="form-group">
            <?= $form->labelEx($model, 'title', array('class' => 'info-title')); ?>
            <?= $form->textField($model, 'title', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'title'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'text', array('class' => 'info-title')); ?>
            <?= $form->textArea($model, 'text', array('class' => 'form-control', 'rows' => 15)); ?>
            <?= $form->error($model, 'text'); ?>
        </div>
        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('ForumModule.default', 'ADD_TOPIC'), array('class' => 'btn btn-primary')); ?>
        </div>

    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Опции модератора</div>
            <div class="panel-body">
                <div class="form-inline">
                    <label for="exampleInputFile">Лейбел</label>
                    <select class="form-control">
                        <option>test1</option>
                        <option>test1</option>
                    </select>
                    <p class="text-muted">Example block-level help text here.</p>
                </div>
                <div class="">
                       <?= $form->checkbox($model, 'is_close', array()); ?>
                    <?= $form->label($model, 'is_close', array('class' => '')); ?>
                 

                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>