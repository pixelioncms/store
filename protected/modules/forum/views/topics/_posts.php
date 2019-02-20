<div class="panel panel-default forum-post" name="post-<?= ($index + 1) ?>" id="post-<?= ($index + 1) ?>">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <?= ($data->user) ? $data->user->login : Yii::t('app', Yii::app()->user->guestName); ?>
        </div>
        <div class="pull-right">
            <?php if (Yii::app()->user->isSuperuser) { ?>
                <?= CMS::ip($data->ip_create) ?>
            <?php } ?>
            <input type="checkbox" name="ads" class="" />
            <?= Html::link('#' . ($index + 1), '#post-' . ($index + 1)); ?>
            <?= Html::link(Html::tag('i', array('class' => 'icon-share'), '', true), '', array('class' => 'btn btn-link', 'style' => 'padding:0;')); ?>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-4 text-center">

                <div style="margin:0 auto;">

                    <?php if ($data->user) { ?>
                        <?php echo Html::image($data->user->getAvatarUrl("100x100"), $data->user->username, array('class' => 'img-thumbnail')) ?>

                    <?php } else { ?>

                        <?php echo Html::image(Yii::app()->user->getAvatarUrl("100x100", true), Yii::app()->user->guestName, array('class' => 'img-thumbnail')) ?>


                    <?php } ?>

                </div>
                <?php if ($data->user) { ?>
                    <div><?= $data->user->rolesList[0]->description ?></div>
                    <div><?= Yii::t('ForumModule.default', 'MESSAGES', array('{num}' => $data->user->forum_posts_count)) ?></div>
                <?php } ?>

            </div>
            <div class="col-md-10 col-sm-9 col-xs-8">
                <div class="text-muted"><?= Yii::t('ForumModule.default', 'POST_SENDDATE'); ?> <?= CMS::date($data->date_create, true, true); ?></div>
                <div id="post-edit-ajax-<?= $data->id; ?>">
                    <?php $this->renderPartial('_posts_content', array('data' => $data)); ?>


                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer text-right">



        <a class="btn btn-xs btn-link" href="#">Скрыть</a>
        <a class="btn btn-xs btn-link" href="#">Жалоба</a>
        <?php if (!Yii::app()->user->isGuest) { ?>
            <a href="#" class="quote btn btn-xs btn-default">Цитата each</a>
            <a href="/forum/quote?post_id=<?= $data->id ?>" class="quote btn btn-xs btn-default">Ответить</a>
        <?php } ?>

        <?php if ($data->isEditPost()) { ?>

            <?php
            echo CHtml::ajaxLink('<i class="icon-edit"></i> Изменить', array('/forum/topics/editpost', 'id' => $data->id), array(
                'type' => 'GET',
                'data' => array(),
                'success' => 'js:function(data){
                    $("#post-edit-ajax-' . $data->id . '").html(data);
                    common.removeLoader();
                }',
                'beforeSend' => 'js:function(){
                    common.addLoader();
                }'
                    ), array('class' => 'btn btn-xs btn-link'));
            ?>


        <?php } ?>
        <?php if (Yii::app()->settings->get('forum', 'enable_post_delete')) { ?>
            <a class="btn btn-xs btn-link" href="#"><i class="icon-delete"></i> <?= Yii::t('app', 'DELETE') ?></a>
        <?php } ?>



    </div>
</div>
