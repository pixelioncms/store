<div class="sign-in-page">
    <div class="row">
        <div class="col-md-6 col-sm-6 sign-in">
            <h4 class="">Вход</h4>
            <p class=""><?= Yii::t('UsersModule.default', 'SIGNIN_TEXT_LOGIN') ?></p>
            <?php $this->renderPartial('form_login', array('model' => $login)); ?>
        </div>
        <div class="col-md-6 col-sm-6 create-new-account">
            <h4 class="checkout-subtitle"><?= $this->pageName; ?></h4>
            <p class="text title-tag-line"><?= Yii::t('UsersModule.default', 'SIGNIN_TEXT_REG') ?></p>
            <?php $this->renderPartial('form_register', array('model' => $register)); ?>
        </div>	
    </div>
</div>


