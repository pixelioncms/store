<?php
$total_posts = 0;
?>
<div class="forum">
    <h1><?= $this->pageName; ?></h1>

    <?php foreach ($categories as $category) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading">

                <?= $category->name ?>
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <?php
                        foreach ($category->children()->published()->findAll() as $data) {
                            $total_posts += $data->count_posts;
                            $this->renderPartial('partials/_categories_list', array('data' => $data));
                        }
                        ?>
                </div>
                </table>
            </div>
        </div>
    <?php } ?>


    <div class="col-md-3 text-center"><span class="badge"><?= $total_posts ?></span> Всего сообщений</div>
    <div class="col-md-3 text-center"><span class="badge"><?= User::model()->count(); ?></span> Пользователей</div>
    <div class="col-md-3 text-center"><span class="badge"><?php echo User::model()->lastRecord()->find()->login; ?></span> Новый участник</div>
    <div class="col-md-3 text-center"><span class="badge">2</span> Рекорд посещаемости </div>




    <div class="">
        <div class="">Share block</div>

        <?php
        $session = Session::model()->findAllByAttributes(array('current_url' => Yii::app()->request->url));
        ?>

        <div><?= Yii::t('ForumModule.default', ($this->id == 'topics') ? 'VIEW_MEMBERS_TOPIC' : 'VIEW_MEMBERS_CAT', array('{num}' => count($session))); ?></div>
        <?php
        $total = 0;
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
            $total++;
        }
        ?>
        <div>Пользователей онлайн: <?= $total ?> (за последние 15 минут)</div>

        admin 


    </div>








    <div>

        <a class="btn btn-link" href="#">Администрация</a>
        <a class="btn btn-link" href="#">Самые активные сегодня</a>
        <a class="btn btn-link" href="#">Самые активные Самый</a>
        <a class="btn btn-link" href="#">Популярный Контент</a>



    </div>
</div>

