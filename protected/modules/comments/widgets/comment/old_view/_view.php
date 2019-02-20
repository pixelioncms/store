
<div id="commend_<?= $data->id ?>" class="comment">

    <div class="comment-author">
        <img src="<?= Html::encode($data->user_avatar); ?>">
    </div>
    <div class="comment-content">
        <div class="comment-header">
            <b><?= Html::encode($data->user_name); ?></b>, сказал: <?= Html::link('#' . $data->id, Yii::app()->request->getUrl() . '#comment_' . $data->id) ?>
            <div class="comment-date"><?= CMS::date($data->date_create); ?></div>
                            <?php
// $this->controller->widget('ext.like.LikeWidget',array('model'=>$data));

?>
            <?php if($data->controlTimeout()){ ?>
            <div class="comment-panel" id="comment-panel<?= $data->id?>">
                <?= $data->editLink ?>
                <?= $data->deleteLink ?>

            </div>
            <?php } ?>
        </div>

        <div id="comment_<?= $data->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($data->text))); ?></div>
        <a class="reply" href="javascript:void(0)" onClick='$("#comment_<?= $data->id; ?>").comment("reply_form",{pk:"<?= $data->id; ?>", model:"<?= $data->model; ?>"}); return false;'>ответить</a>
    </div>
    <div class="container-reply" id="comment-reply-form-<?= $data->id; ?>"></div>
    <?php
    $descendants = $data->descendants()->findAll(array('order' => '`t`.`rgt` DESC'));
    if (isset($descendants)) {
        foreach ($descendants as $children) {
            $margin_left = ($children->level == 2) ? ($children->level * 3) . '0px' : ($children->level * 3) . '0px';
            ?>
            <div lavel="<?= $children->level ?>" class="comment level-<?= $children->level ?> comment-child">
                <div class="comment-author">
                    <img src="<?= Html::encode($children->user_avatar); ?>">

                </div>
                <div class="comment-content">
                    <div class="comment-header">
                        <b><?= Html::encode($children->user_name); ?></b>, сказал: <?= Html::link('#' . $children->id, Yii::app()->request->getUrl() . '#comment_' . $children->id) ?>
                        <div class="comment-date"><?= CMS::date($children->date_create); ?></div>
                        <?php if($children->controlTimeout()){ ?>
                        <div class="comment-panel">
                            <?= $children->editLink ?>
                            <?= $children->deleteLink ?>
                        </div>
                        <?php } ?>
                    </div>
                    <div id="comment_<?= $children->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($children->text))); ?></div>
                    <a class="reply" href="javascript:void(0)" onClick='$("#comment_<?= $children->id; ?>").comment("reply_form",{pk:"<?= $children->id; ?>", model:"<?= $children->model; ?>"}); return false;'><?= Yii::t('CommentsModule.default','REPLY');?></a>
                </div>
            </div>
            <div class="container-reply" id="comment-reply-form-<?= $children->id; ?>"></div>
            <?php
        }
    }
    ?>

</div>






