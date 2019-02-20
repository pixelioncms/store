<?php
$title = $this->pageName;
$msg = (isset($error['message']) && !empty($error['message'])) ? $error['message'] : Yii::t('error', $error['code']);
?>

<h1 class="text-center"><?= $this->pageName ?> <?= $error['code'] ?></h1>
<h3 class="text-center"><?= $msg ?></h3>

<?php if (YII_DEBUG) { ?>
    <div class="alert alert-info">
        <p>Файл: <b><?= $error['file'] ?></b></p>
        <p>Cтрока: <b><?= $error['line'] ?></b></p>
        <p><a class="btn btn-xs btn-primary" role="button" data-toggle="collapse" href="#trace" aria-expanded="false" aria-controls="trace">Отследить</a></p>
    </div>
    <div class="collapse" id="trace">
        <pre><?php echo($error['trace']) ?></pre>
    </div>
<?php } ?>