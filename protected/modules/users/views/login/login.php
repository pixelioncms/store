<div class="col-xs-18">
    <h1><?= $this->pageName ?></h1>
</div>
<div class="col-sm-7 col-sm-offset-6" id="login-form">
    <?php
    $this->renderPartial('_form', array('model' => $model));
    ?>

    <?php
    //if (Yii::app()->hasComponent('eauth')) {
        Yii::app()->eauth->renderWidget();
   // }
    ?>
</div>
