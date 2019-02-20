<?php
$subCategories = $model->children()->findAll();
?>
<div class="forum">

    <h1><?= $model->name ?></h1>


    <?php if (count($subCategories) > 0) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading">

                Подфорумы
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">


                        <?php
                        foreach ($subCategories as $data) {
                            $this->renderPartial('partials/_categories_list', array('data' => $data));
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>

    <?php } ?>











    <?php $this->renderPartial('partials/_addtopic', array('model' => $model)); ?>
    <div class="clearfix"></div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $model->name ?>
        </div>
        <div class="panel-body">
            <?php if ($model->topics) { ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed">


                        <?php foreach ($model->topicsList as $data) { ?>
                            <tr>
                                <td class="text-center"  width="2%">
                                    <?php if ($data->is_close) { ?>
                                        <i class="icon-locked" title="Тема закрыта"></i>
                                    <?php } ?>

                                    <?php if ($data->user_id == Yii::app()->user->id) { ?>
                                        <i class="icon-envelope" style="font-size:24px;"></i>

                                    <?php } else { ?>

                                        <i class="icon-star" style="font-size:24px;color:#ccc" title="Вы оставили сообщение в этой теме"></i>


                                    <?php } ?>


                                </td>
                                <td>
                                    <?php if ($data->fixed) { ?>
                                        <span class="badge badge-success"><?= Yii::t('ForumModule.default', 'FIXED'); ?></span>
                                    <?php } ?>
                                    <?php if ($data->user_id == Yii::app()->user->id) { ?>
                                        <?= Html::link('<b>' . $data->title . '</b>', $data->getUrl()) ?>
                                    <?php } else { ?>

                                        <?= Html::link($data->title, $data->getUrl()) ?>
                                    <?php } ?>
                                    <br/>
                                    <?php if ($data->user) { ?>
                                        Автор: <?= Html::link($data->user->login, $data->user->getProfileUrl()) ?>,
                                    <?php } else { ?>
                                        <?= Yii::t('app', Yii::app()->user->guestName); ?>,
                                    <?php } ?>
                                    <?= CMS::date($data->date_create,true,true) ?>

                                    <?php
                                    $per_page = (int) Yii::app()->settings->get('forum', 'pagenum');
                                    //узнаем общее количество страниц и заполняем массив со ссылками
                                    $num_pages = ceil($data->postsCount / $per_page);
                                    if ($data->postsCount >= $per_page) {
                                        ?>



                                        <ul class="pagination pagination-xs">
                                            <?php for ($i = 1; $i <= $num_pages; $i++) { ?>
                                                <?php if ($i <= 3) { ?>
                                                    <li><a href="<?= Yii::app()->createUrl('/forum/topics/view', array('id' => $data->id, 'page' => $i)) ?>" title="<?= CMS::date($data->date_create,true,true) ?> <?= $data->title ?> Перейти к странице <?= $i ?>"><?= $i ?></a></li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($num_pages >= 3) { ?>
                                                <li><a href="<?= Yii::app()->createUrl('/forum/topics/view', array('id' => $data->id, 'page' => $num_pages)) ?>" title="<?= CMS::date($data->date_create,true,true) ?> <?= $data->title ?> Перейти к странице <?= $num_pages ?>"><?= $num_pages ?> &rarr;</a></li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </td>
                                <td width="15%" class="text-right">
                                    <div><b><?= $data->views ?></b> <?= CMS::GetFormatWord('ForumModule.default', 'VIEWS', $data->views); ?></div>
                                    <div><b><?= ($data->postsCount > 0) ? $data->postsCount - 1 : 0 ?></b> <?= CMS::GetFormatWord('ForumModule.default', 'POSTS', ($data->postsCount > 0) ? $data->postsCount - 1 : 0); ?></div>
                                <td width="20%">

                                    <?php if ($data->postsCount > 0) { ?>
                                        <?php if ($data->postsDesc[0]->user) { ?>
                                            <?= Html::link($data->postsDesc[0]->user->login, $data->postsDesc[0]->user->getProfileUrl()) ?>
                                        <?php } else { ?>
                                            ГОСТЬ!
                                        <?php } ?>





                                        <br/>
                                        <?= CMS::date($data->postsDesc[0]->date_create,true,true); ?>
                                    <?php } else { ?>

                                        <div class="text-center"><?= Yii::t('ForumModule.default', 'NO_MESSAGES'); ?></div>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            <?php } else { ?>
                <div class="alert alert-info">Нет тем. Такое может быть если тем действительно в данном форуме еще не создавалось, или если выставлены фильтры просмотра списка тем.</div>

            <?php } ?>
        </div>
    </div>
    <?php $this->renderPartial('partials/_addtopic', array('model' => $model)); ?>



    <div class="">
        <div class="">Share block</div>

        <?php
        $session = Session::model()->findAllByAttributes(array('current_url' => Yii::app()->request->url));
        ?>

        <div><?= Yii::t('ForumModule.default', ($this->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', array('{num}' => count($session))); ?></div>
        <?php
        $t = 0;
        $guests = 0;
        $bots = 0;
        $users = 0;

        foreach ($session as $val) {

            if ($val->user_type == 2 || $val->user_type == 3) {
                $users++;
            } elseif ($val->user_type == 1) {
                $bots++;
            } else {
                $guests++;
            }
            $t++;
        }
        ?>
        <div><?= $users ?> пользователей, <?= $guests ?> гостей, N/A анонимных</div>

        admin 


    </div>
</div>