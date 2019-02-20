
    <div class="blog-post wow fadeInUp outer-bottom-bd">
    <?php $this->widget('mod.users.widgets.favorites.FavoritesWidget', array('model' => $data)); ?>
    <?php $this->widget('ext.admin.frontControl.FrontControlWidget', array('data' => $data, 'options' => array('position' => 'right'))); ?>
    <h1><?= Html::link($data->title, $data->getUrl(), array('title' => $data->title)) ?></h1>

    <?php if ($data->user) { ?>
        <span class="author"><?= Html::link($data->user->login, array('/users/profile/view', 'user_id' => $data->user->id)) ?></span>
    <?php } else { ?>
        <span class="author"><?= Yii::t('app', 'CHECKUSER', 0) ?></span>
    <?php } ?>
    <?php if (isset($data->commentsCount)) { ?><span class="review"><?= $data->commentsCount; ?> Комментариев</span><?php } ?>
    <span class="date-time"><?= CMS::date($data->date_create) ?></span>
    <?= Html::text($data->short_text); ?>
    <?php if ($data->tagLinks) { ?><b><?= Yii::t('app', 'TAGS') ?>:</b> <?php echo implode(', ', $data->tagLinks); ?><?php } ?>
    <?= Html::link(Yii::t('app', 'MORE'), $data->getUrl(), array('class' => 'btn btn-upper btn-primary read-more', 'title' => Html::decode(Yii::t('app', 'MORE')))) ?>
</div>








