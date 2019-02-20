<?php

class ScheduleWidget extends BlockWidget {
    public function getTitle() {
        return Yii::t('default', 'Расписание');
    }
    public function run() {
        $model = Yii::app()->controller->getContacts();
        if ($model) {
            $this->render($this->skin, array('model' => $model));
        }
    }

}
