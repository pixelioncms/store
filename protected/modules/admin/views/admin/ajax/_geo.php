<?php
$geoip = Yii::app()->geoip->get($ip);
//&key=AIzaSyDeuUyP2Q072o5c8LgF0aPXlG_n712XLd4

?>

<div class="row">
    <div class="col-sm-6">
        <img class="img-fluid img-thumbnail"
             src="//maps.googleapis.com/maps/api/staticmap?center=<?= $geoip->location->lat ?>,<?= $geoip->location->lng ?>&zoom=13&key=AIzaSyBfXgobbZPa6KOHExMBdsC4EvIuKsOQ0DE&size=350x350&scale=1&maptype=roadmap&format=png&language=<?= Yii::app()->language ?>&markers=color:red|<?= $geoip->location->lat ?>,<?= $geoip->location->lng ?>"
             alt="">
    </div>
    <div class="col-sm-6">
        <?= CMS::ip($ip, 0); ?><?php echo $geoip->country; ?>
        <?php if ($geoip->city) { ?>
            <div><b><?= Yii::t('app', 'City') ?>:</b> <?= $geoip->city; ?></div>
        <?php } ?>

        <?php if ($geoip->region) { ?>
            <div><b>Регион:</b> <?= $geoip->region; ?></div>
        <?php } ?>
        <?php if ($geoip->postal) { ?>
            <div><b>Индекс:</b> <?= $geoip->postal; ?></div>
        <?php } ?>
        <?php if ($geoip->org) { ?>
            <div><b>Организация:</b> <?= $geoip->org; ?></div>
        <?php } ?>
        <hr/>
        <?php
        $users = User::model()->findAllByAttributes(array('login_ip' => $ip));
        if (!empty($users)) {
            echo 'Пользователи заходившие с этого IP-адреса:<br>';
            foreach ($users as $user) {
                echo Html::link($user->login, array('/admin/users/default/update', 'id' => $user->id)) . '<br>';
            }
        }
        ?>

        <?php
        $sessions = Yii::app()->db->createCommand(array(
            'select' => array('*'),
            'from' => Session::model()->tableName(),
            //'distinct' => true,
            //'group' => 'ip_address',
            'where' => 'ip_address=:ip',
            'params' => array(':ip' => $ip),
        ))->queryAll();


        if ($sessions) {
            echo '<hr/><b>Сессии:</b><br>';
            foreach ($sessions as $session) {
                if (!in_array($session['user_type'], array('Guest', 'SearchBot'))) {
                    echo Rights::getRoles()[$session['user_type']].'<br/>';
                } else {
                    echo Yii::t('app',strtoupper($session['user_type'])).'<br/>';
                }
            }
        }
        ?>


        <div class="card card-light" style="display:none;">
            <div class="card-body">

                <ul class="list-group">
                    <li class="list-group-item"><?= CMS::ip($ip, 0); ?><?php echo Yii::t('CGeoIP.country', $geoip->countryCode); ?></li>
                    <li class="list-group-item"><?php echo Yii::t('CGeoIP.region', $geoip->region); ?></li>
                    <li class="list-group-item"><?php echo Yii::t('CGeoIP.city', $geoip->city); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>



