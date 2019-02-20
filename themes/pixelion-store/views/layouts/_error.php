<?php
$message = (empty($error['message'])) ? Yii::t('error', $error['code']) : $error['message'];
?>


<h1><?= $error['code'] ?></h1>
<p><?= $message ?></p>
<?= Html::link('<i class="fa fa-home"></i> ' . Yii::t('zii', 'Home'), array('/main/default/index'), array('class' => '')); ?>


<div class="alert alert-info">
    <p>Файл: <b><?= $error['file'] ?></b></p>
    <p>Cтрока: <b><?= $error['line'] ?></b></p>
    <p><a class="btn btn-xs btn-primary" role="button" data-toggle="collapse" href="#trace" aria-expanded="false"
          aria-controls="trace">Отследить</a></p>
</div>
<div class="collapse" id="trace">
    <pre><?php echo($error['trace']) ?></pre>
</div>

