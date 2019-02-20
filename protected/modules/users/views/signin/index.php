<div class="row">
    <div class="col-md-6 col-sm-6">
        <h4><?= $this->pageName; ?></h4>
        <p><?=Yii::t('UsersModule.default','SIGNIN_TEXT_REG')?></p>
        <?php $this->renderPartial('form_register', array('model' => $register)); ?>
    </div>

    <div class="col-md-6 col-sm-6">
        <h4>Вход</h4>
        <p><?=Yii::t('UsersModule.default','SIGNIN_TEXT_LOGIN')?></p>
        <?php $this->renderPartial('form_login', array('model' => $login)); ?>
    </div>	
</div>

