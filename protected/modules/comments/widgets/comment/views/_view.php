<div id="commend_<?= $data->id ?>" name="commend_<?= $data->id ?>" class="comment">
    <div class="comment-author">
        <?php
        echo Html::image($data->user->getAvatarUrl('50x50'),'');

        ?>
    </div>
    <div class="comment-box">
        <div class="comment-header">
            <b><?= Html::encode($data->user_name); ?></b>,
            сказал: <?= Html::link('#' . $data->id, Yii::app()->request->getUrl() . '#comment_' . $data->id) ?>


            <div class="float-right">
                <small class="comment-date"><?= CMS::date($data->date_create); ?></small>
                <?php
                //$this->controller->widget('ext.like.LikeWidget',array('model'=>$data));
                ?>
                <?php if ($data->controlTimeout()) { ?>

                    <div class="btn-group btn-group-sm" id="comment-panel<?= $data->id ?>">
                        <?= $data->editLink ?>
                        <?= $data->deleteLink ?>

                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="comment-content"
             id="comment_<?= $data->id; ?>"><?= nl2br(Html::text($data->text)); ?></div>
        <a class="btn-reply btn btn-link" href="javascript:void(0)"
           onClick='$("#comment_<?= $data->id; ?>").comment("reply_form",{pk:"<?= $data->id; ?>", model:"<?= $data->model; ?>"}); return false;'><?= Yii::t('CommentsModule.default', 'REPLY'); ?></a>
        <div class="clearfix"></div>
    </div>

    <div class="container-reply" id="comment-reply-form-<?= $data->id; ?>"></div>
    <?php
    $descendants = $data->descendants()->findAll(array('order' => '`t`.`rgt` DESC'));
    if (isset($descendants)) {
        foreach ($descendants as $children) {
            ?>
            <div lavel="<?= $children->level ?>" name="commend_<?= $children->id ?>"
                 class="comment level-<?= $children->level ?> comment-child">
                <div class="comment-author">
                    <?php
                    echo Html::image($children->user->getAvatarUrl('50x50'),'');

                    ?>

                </div>
                <div class="comment-box">
                    <div class="comment-header">
                        <b><?= Html::encode($children->user_name); ?></b>,
                        сказал: <?= Html::link('#' . $children->id, Yii::app()->request->getUrl() . '#comment_' . $children->id) ?>

                        <div class="float-right">
                            <small class="comment-date"><?= CMS::date($children->date_create); ?></small>

                            <?php
                            //$this->controller->widget('ext.like.LikeWidget',array('model'=>$children,));
                            ?>

                            <?php if ($children->controlTimeout()) { ?>


                                <div class="btn-group btn-group-sm" id="comment-panel<?= $data->id ?>">
                                    <?= $children->editLink ?>
                                    <?= $children->deleteLink ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="comment-content"
                         id="comment_<?= $children->id; ?>"><?= nl2br(Html::text($children->text)); ?></div>
                    <a class="btn-reply btn btn-link" href="javascript:void(0)"
                       onClick='$("#comment_<?= $children->id; ?>").comment("reply_form",{pk:"<?= $children->id; ?>", model:"<?= $children->model; ?>"}); return false;'><?= Yii::t('CommentsModule.default', 'REPLY'); ?></a>
                    <div class="clearfix"></div>
                </div>
                <div class="container-reply" id="comment-reply-form-<?= $children->id; ?>"></div>
            </div>

            <?php
        }
    }
    ?>

</div>






