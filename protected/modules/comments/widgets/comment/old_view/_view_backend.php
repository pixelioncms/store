<ul class="messagesOne">
        <li class="divider"><span></span></li>
    <li class="by_user">
        <a href="#" title=""><img src="<?= Html::encode($data->user_avatar); ?>" width="50" alt="" /></a>
        <div class="messageArea">
            <span class="aro"></span>
            <div class="infoRow">
                <span class="name"><strong><?= Html::encode($data->user_name); ?></strong> сказал:</span>
                <span class="time"><?= CMS::date($data->date_create); ?></span>
                <?php if ($data->controlTimeout()) { ?>
                    <div class="comment-panel" id="comment-panel<?= $data->id ?>">
                        <?= $data->editLink ?>
                        <?= $data->deleteLink ?>

                    </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div id="comment_<?= $data->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($data->text))); ?></div>
            <a class="reply" href="javascript:void(0)" onClick='$("#comment_<?= $data->id; ?>").comment("reply_form",{pk:"<?= $data->id; ?>", model:"<?= $data->model; ?>"}); return false;'>ответить</a>
        </div>
        <div class="clear"></div>
    </li>

    
    
    <?php
    $descendants = $data->descendants()->findAll(array('order' => '`t`.`rgt` DESC'));
    if (isset($descendants)) {
        foreach ($descendants as $children) {
            $margin_left = ($children->level == 2) ? ($children->level * 3) . '0px' : ($children->level * 3) . '0px';
            ?>
    
    
    
    <li class="by_user comment comment-child level-<?= $children->level ?> " lavel="<?= $children->level ?>">
        <a href="#" title=""><img src="<?= Html::encode($children->user_avatar); ?>" width="50"  alt="" /></a>
        <div class="messageArea">
            <span class="aro"></span>
            <div class="infoRow">
                <span class="name"><strong><?= Html::encode($children->user_name); ?></strong> сказал:</span>
                <span class="time"><?= CMS::date($children->date_create); ?></span>
                                       <?php if ($children->controlTimeout()) { ?>
                            <div class="comment-panel">
                                <?= $children->editLink ?>
                                <?= $children->deleteLink ?>
                            </div>
                        <?php } ?>
                <div class="clear"></div>
            </div>
            <div id="comment_<?= $children->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($children->text))); ?></div>
            <a class="reply" href="javascript:void(0)" onClick='$("#comment_<?= $children->id; ?>").comment("reply_form",{pk:"<?= $children->id; ?>", model:"<?= $children->model; ?>"}); return false;'><?= Yii::t('CommentsModule.default', 'REPLY'); ?></a>
        </div>
                    <div class="container-reply" id="comment-reply-form-<?= $children->id; ?>"></div>
            
        <div class="clear"></div>
    </li>
    
    

            
            <?php
        }
    }
    ?>
    

    



</ul>







