


<div class="blog-page">
    <div class="blog-post wow fadeInUp outer-bottom-bd">
        <?php $this->widget('mod.users.widgets.favorites.FavoritesWidget', array('model' => $model)); ?>
        <?php $this->widget('ext.admin.frontControl.FrontControlWidget', array('data' => $model, 'options' => array('position' => 'right'))); ?>
        <h1><?= $model->isString('title'); ?></h1>

        <?php if ($model->user) { ?>
            <span class="author"><?= Html::link($model->user->login, array('/users/profile/view', 'user_id' => $model->user->id)) ?></span>
        <?php } else { ?>
            <span class="author"><?= Yii::t('app', 'CHECKUSER', 0) ?></span>
        <?php } ?>
        <?php if (isset($model->commentsCount)) { ?><span class="review"><?= $model->commentsCount; ?> Комментариев</span><?php } ?>
        <span class="date-time"><?= CMS::date($model->date_create) ?></span>
        <?= $model->isArea('full_text'); ?>
        <?php if ($model->tagLinks) { ?><b><?= Yii::t('app', 'TAGS') ?>:</b> <?php echo implode(', ', $model->tagLinks); ?><?php } ?>

    </div>
</div>

<div class="blog-write-comment outer-bottom-xs outer-top-xs">
    <?php
    if (Yii::app()->hasModule('comments')) {
        $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));
    }
    ?>
</div>


