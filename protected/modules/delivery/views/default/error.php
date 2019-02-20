<?php
$this->pageTitle = Yii::t('site','Error').' '.$error['code'];
?>
<h1><?php echo $error['code'];?></h1>
<b><?php echo Yii::t('site','Error')?></b>
<div style="margin-top: 10px;"><?php echo $error['message'];?></div>