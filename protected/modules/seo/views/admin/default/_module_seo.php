<?php
Yii::import('mod.seo.SeoModule');
Yii::import('mod.seo.models.*');
$url = '';
if ($model->isNewRecord) {
    $modelseo = new SeoUrl;
} else {
    $url = $model->getUrl();
    $modelseo = SeoUrl::model()->findByAttributes(array('url' => $model->getUrl()));
    if (!$modelseo) {
        $modelseo = new SeoUrl;
    }
}
?>
<div class="form-group row">
    <?php echo Html::label('Для адреса', 'seolink', array('class' => 'col-form-label col-sm-4')); ?>
    <div class="col-sm-8">
        <?= Yii::app()->request->serverName; ?><strong class="badge2 badge-secondary2" style=""><?= $url ?></strong>

    </div>
</div>
<div class="form-group row">
    <?php echo Html::activeLabelEx($modelseo, 'title', array('class' => 'col-form-label col-sm-4')); ?>
    <div class="col-sm-8">
        <?php echo Html::activeTextField($modelseo, 'title', array('class' => 'form-control')); ?>
        <?php echo Html::error($modelseo, 'title'); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo Html::activeLabelEx($modelseo, 'description', array('class' => 'col-form-label col-sm-4')); ?>
    <div class="col-sm-8">
        <?php echo Html::activeTextArea($modelseo, 'description', array('class' => 'form-control')); ?>
        <?php echo Html::error($modelseo, 'description'); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo Html::activeLabelEx($modelseo, 'keywords', array('class' => 'col-form-label col-sm-4')); ?>
    <div class="col-sm-8">
        <?php
        $this->widget('ext.tageditor.TagEditor', array(
            'attribute' => 'keywords',
            'model' => $modelseo
        ));
        ?>
        <div class="text-muted"><?= $modelseo::t('KEYWORDS_HINT'); ?></div>
        <?php //echo Html::activeTextField($modelseo, 'keywords', array('class' => 'form-control')); ?>
        <?php //echo Html::error($modelseo, 'title'); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo Html::activeLabelEx($modelseo, 'h1', array('class' => 'col-form-label col-sm-4')); ?>
    <div class="col-sm-8">
        <?php echo Html::activeTextField($modelseo, 'h1', array('class' => 'form-control')); ?>
        <?php echo Html::error($modelseo, 'h1'); ?>
    </div>
</div>
<div class="form-group row">
    <?php echo Html::activeLabelEx($modelseo, 'text', array('class' => 'col-form-label col-sm-4')); ?>
    <div class="col-sm-8">
        <?php
        $this->widget('ext.tinymce.TinymceArea', array(
            'model' => $modelseo,
            'attribute' => 'text'
        ))
        ?>
        <?php echo Html::error($modelseo, 'text'); ?>
    </div>
</div>