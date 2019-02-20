
<tr>
    <td>
        <?= Html::link($data->name, $data->getUrl()) ?> <?= Html::link('<i class="icon-add"></i>', Yii::app()->createUrl('/forum/default/addCat', array('parent_id' => $data->id)), array('class' => 'btn2 btn-xs2 btn-success')) ?>
        <div class="text-muted"><?= $data->hint ?></div>


    </td>
    <td width="15%" class="text-right">
        <div><b><?= $data->count_topics ?></b> <?= CMS::GetFormatWord('ForumModule.default', 'TOPICS', $data->count_topics); ?></div>
        <div><b><?= $data->count_posts ?></b> <?= CMS::GetFormatWord('ForumModule.default', 'POSTS', $data->count_posts); ?></div>
    </td>
    <td width="20%">



        <?php if ($data->topicsCount > 0) { ?>
            <div class="last_post_avatar">
                <?php if ($data->topics[0]->user) { ?>
                    <?php echo Html::image($data->topics[0]->user->getAvatarUrl("25x25"), $data->topics[0]->title . ' - последнее сообщение от ' . $data->topics[0]->user->login, array('class' => 'img-thumbnail')) ?>
                <?php } else { ?>
                    <?php echo Html::image(Yii::app()->user->getAvatarUrl("25x25", true), $data->topics[0]->title . ' - последнее сообщение от ' . Yii::app()->user->guestName, array('class' => 'img-thumbnail')) ?>

                <?php } ?>
            </div>
            <div class="last_post">
                <?php
                echo Html::link($data->topics[0]->title, $data->topics[0]->getUrl());
                ?>
                <br>
                От <?php echo ($data->lastPost->user) ? Html::link($data->lastPost->user->login, $data->lastPost->user->getProfileUrl()) : 'гость'; ?>
                <br/>
                <?= CMS::date($data->lastPost->date_create,true,true); ?>
            </div>
        <?php } else { ?>
            <span><?= Yii::t('ForumModule.default', 'NO_MESSAGES') ?></span>
        <?php } ?>
    </td>
</tr>
