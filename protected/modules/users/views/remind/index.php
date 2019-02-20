<h1><?php echo $this->pageName; ?></h1>
<div class="text-muted"><?=Yii::t('UsersModule.default','REMIND_HELP_TEXT'); ?></div>
<?php
if(Yii::app()->user->hasFlash('success')){
    Yii::app()->tpl->alert('success',Yii::app()->user->getFlash('success'));
}
?>
<?php
echo Html::form();
?>

<?= Html::activeLabel($model, 'email', array('required' => true)); ?>

<div class="input-group">
    <span class="input-group-addon">@</span>
    <?= Html::activeTextField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control','style'=>'width:320px')); ?>
    
</div>
<div><?= Html::error($model, 'email'); ?></div>
<br/>
<input type="submit" class="btn btn-primary" value="<?php echo Yii::t('UsersModule.default', 'REMIND_BUTTON'); ?>">
<br/><br/>
<ul class="list-unstyled">
    <li><?= Html::link(Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register')); ?></li>
    <li><?= Html::link(Yii::t('UsersModule.default', 'AUTH'), array('/users/login')); ?></li>
</ul>

<?= Html::endForm(); ?>
