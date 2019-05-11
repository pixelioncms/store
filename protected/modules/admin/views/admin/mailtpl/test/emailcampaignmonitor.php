<?php
$assetsUrl = Yii::app()->assetManager->publish(
    Yii::getPathOfAlias('mod.admin.views.admin.mailtpl.test'), false, -1, YII_DEBUG
);
?>
<html>

<body>

<img src="<?= Yii::app()->createAbsoluteUrl('/mails/1.jpg');?>" alt="" />

</body>
</html>
