<?php
$this->title = $event->sender->getStepLabel($event->step);
$this->process = Yii::t('InstallModule.default', 'STEP', array(
            '{current}' => $event->sender->currentStep,
            '{count}' => $event->sender->stepCount
        ));
?>

<div class="row">
    <div class="col-sm-3">
        <?= $event->sender->menu->run(); ?>
    </div>
    <div class="col-sm-9">
        <div class="form-block clearfix">
            <?= $form; ?>
        </div>
    </div>
</div>



