<h1><?= $model->title ?></h1>
<small>Автор <?= $model->user->login ?>, <?= CMS::date($model->date_create, true, true) ?></small>
<div class="forum">
    <div class="form-group pull-right">
        <div class="dropdown">
            <a class="btn btn-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Опции модератора
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a href="#"><i class="icon-rename"></i> Редактировать заголовок</a></li>
                <li><a href="#">Открыть тему</a></li>
                <li><a href="#"><i class="icon-move"></i> Переместить тему</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Объединить тему</a></li>
                <li><a href="#">Скрыть</a></li>
                <li style="display:none;"><a href="#">Посмотреть историю (опция администратора)</a></li>
                <li style="display:none;"><a href="#">Отписать всех от этой темы</a></li>
                <li><a href="#"><i class="icon-delete"></i> <?= Yii::t('app', 'DELETE') ?></a></li>
            </ul>
            <?php if ($model->is_close) { ?>
                <a href="#" class="btn btn-danger"><i class="icon-locked"></i>  Закрыта (нажмите для ответа)</a>
            <?php } ?>
        </div>


    </div>
    <div class="clearfix"></div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?php
            if (count($model->posts) >= 1) {
                echo Yii::t('ForumModule.default', 'POST_MESSAGES_NUM', array('{count}' => count($model->posts) - 1));
            } elseif (count($model->posts) <= 1) {
                echo Yii::t('ForumModule.default', 'POST_MESSAGES_NO');
            } else {
                echo Yii::t('ForumModule.default', 'POST_MESSAGES_ONE');
            }
            ?>

        </div>
        <div class="panel-body  bg-info2">


            <?php
            $this->widget('ListView', array(
                'dataProvider' => $providerPosts,
                'id' => 'topic-list',
                'ajaxUpdate' => true,
                'template' => '{items} {pager}',
                'itemView' => '_posts',
                'pagerCssClass' => 'page text-center',
                'enableHistory' => true,
                'pager' => array(
                    'header' => '',
                ),
            ));
            ?>


        </div>
    </div>
    <br/><br/>

    <?php if (!Yii::app()->user->isGuest) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Ответить
            </div>
            <div class="panel-body">
                <?php if ($model->is_close) { ?>
                    <div class="alert alert-info"><span class=" text-danger">Обратите внимание, что эта тема закрыта, но вы можете отвечать в закрытые темы.</span></div>
                <?php } ?>

                <div id="ajax-addreply">
                    <?php
                    $this->renderPartial('_form_addreply', array('model' => $model, 'postModel' => array()));
                    ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">Чтобы оставить сообщение необходимо войти на сайт.</div>
    <?php } ?>


    <div class="">
        <div class="">Share block</div>

        <?php
        $session = Session::model()->with('user')->findAllByAttributes(array('current_url' => Yii::app()->request->url));
        ?>

        <h4><?= Yii::t('ForumModule.default', ($this->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', array('{num}' => count($session))); ?></h4>
        <?php
        $t = 0;
        $guests = 0;
        $bots = 0;
        $users = 0;
        $readNames = array();
        foreach ($session as $val) {

            if ($val->user_type == 2 || $val->user_type == 3) {
                $users++;

                if ($val->user) {

                    $arrayAuthRoleItems = Yii::app()->authManager->getAuthItems(2, $val->user->id);
                    $roles = array_keys($arrayAuthRoleItems);


                    foreach ($roles as $role) {
                        if (in_array($role, array('Admin', 'Moderator'))) {
                            $login = Html::tag('b', array('class'=>'text-danger'), $val->user->login, true);
                        } else {
                            $login = $val->user->login;
                        }
                    }

                    $readNames[] = Html::link($login, $val->user->getProfileUrl());
                }
            } elseif ($val->user_type == 1) {
                $bots++;
                $readNames[] = $val->user_login;
            } else {
                $guests++;
            }

            $t++;
        }
        ?>
        <div><?= $users ?> пользователей, <?= $guests ?> гостей, N/A анонимных</div>
        <br/>
        <?php
        echo implode(', ', $readNames);
        ?>


    </div>
</div>



