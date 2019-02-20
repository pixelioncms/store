
<div class="col-sm-4 offset-sm-4" id="login-form">
    <h1><?= $this->pageName ?></h1>
    <?php
    $this->renderPartial('_form', array('model' => $model));
    ?>

    <?php
    if (Yii::app()->hasComponent('eauth')) {
        Yii::app()->eauth->renderWidget(array('action'=>'/users/login'));
    }
    ?>
</div>
