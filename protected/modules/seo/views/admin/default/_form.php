<?php

Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/js/seo.js');
?>


<script>
    $(function () {

        jQuery.fn.exists = function () {
            return this.length > 0;
        };

        $('.addparams').change(function () {
            var val = $('option:selected', this).val();
            var id = $(this).attr('data-id');
            var text = $('option:selected', this).text();
            rowID = text + id;
            rowID = rowID.replace(".", "");

            if (!$('#' + rowID).exists()) {
                $('#container-param-' + id).append('<tr id="' + rowID + '"><td><input type="hidden" name="param[' + id + '][' + val + ']" value="{' + text + '}" /><code>{' + text + '}</code></td><td>'+val+'</td><td class="text-center"><a href="javascript:void(0);" onClick="$(\'#' + rowID + '\').remove();" class=" btn btn-xs btn-danger"><i class="icon-delete"></i></a></td></tr>');
            } else {
                common.notify('Уже добавлен!', 'error');
            }

        });
    });

</script>


<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'seo-url-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => '')
));
?>


<div class="form-group row">
    <?php echo $form->labelEx($model, 'url', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php echo $form->textField($model, 'url', array('size' => 60, 'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'url'); ?>
    </div>
</div>


<div class="form-group row">
    <?php echo $form->labelEx($model, 'meta_robots', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php

        echo $form->CheckBoxList($model,'meta_robots', array(
            'index'=>'index',
            'follow'=>'follow',
            'noindex'=>'noindex',
            'nofollow'=>'nofollow'
        ))


        ?>

        <?php echo $form->error($model, 'meta_robots'); ?>
    </div>
</div>


<div class="form-group row">
    <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php echo $form->textField($model, 'title', array('class' => 'form-control')); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo $form->labelEx($model, 'description', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php echo $form->textArea($model, 'description', array('class' => 'form-control')); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo $form->labelEx($model, 'keywords', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php
        $this->widget('ext.tageditor.TagEditor', array(
            'attribute' => 'keywords',
            'model' => $model
        ));
        ?>
        <div class="text-muted"><?= $model::t('KEYWORDS_HINT'); ?></div>
        <?php echo $form->error($model, 'keywords'); ?>
    </div>
</div>

<div class="form-group row">
    <?php echo $form->labelEx($model, 'h1', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php echo $form->textField($model, 'h1', array('class' => 'form-control')); ?>
        <?php echo $form->error($model, 'h1'); ?>
    </div>
</div>

<div class="form-group row">
    <?php echo $form->labelEx($model, 'text', array('class' => 'col-sm-4 col-form-label')); ?>
    <div class="col-sm-8">
        <?php
        $this->widget('ext.tinymce.TinymceArea', array(
            'attribute' => 'text',
            'model' => $model
        ));
        ?>

        <?php echo $form->error($model, 'text'); ?>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?php echo CHtml::dropDownList('title_param', "[$model->keywords]param", CHtml::listData($this->getParams(), "value", "name", "group"), array("empty" => "Свойства", 'class' => 'selectpicker addparams form-control', 'data-id' => $model->id)); ?>
        <?php echo $this->renderPartial('_formMetaParams', array('model' => $model)); ?></div>
</div>
<div class="form-group row" style="display:none;">
    <div class="col-sm-4"><?php echo CHtml::dropDownList("name", "", array("robots" => "robots", "author" => "author", "copyright" => "copyright"), array("empty" => "change")) ?>
    </div>
    <div class="col-sm-8">
        <?php echo CHtml::button("add meta name", array('class' => "meta-name")); ?>
        <span id="load-meta-name"></span>
    </div>
</div>


<div class="form-group text-center">
    <?php echo CHtml::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
</div>


<?php
$this->endWidget();
Yii::app()->tpl->closeWidget();
?>

