
<h1><?= $model->isString('title'); ?></h1>
<div class="date">
    <span class="icon-calendar-2"></span>
    <?= CMS::date($model->date_create) ?>
</div>
<?= $model->isArea('full_text'); ?>


    <div class="panel-footer">
        <?php if ($model->user) { ?>
            <span class="author"><?= Html::link($model->user->login, array('/users/profile/view', 'user_id' => $model->user->id)) ?></span>
        <?php } else { ?>
            <span class="author"><?= Yii::t('app','CHECKUSER',0)?></span>
        <?php } ?>
        <?php if ($model->tagLinks) { ?><b><?= Yii::t('app', 'TAGS') ?>:</b> <?php echo implode(', ', $model->tagLinks); ?><?php } ?>
    </div>

<?php
//$this->widget('Rating', array('model'=>$model));
?>
<?php
if (Yii::app()->hasModule('comments')) {
    $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));
}
?>



