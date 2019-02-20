<?php

class LicenseCheck extends CApplicationComponent {

    public $urls = array('admin/auth', 'admin');

    public function init() {
        if (LicenseCMS::run()->checkLicense()) {
            $data = LicenseCMS::run()->connected();
            if ($data['status'] == 'error') {
                foreach ($this->urls as $u) {
                    $disable = strpos(Yii::app()->request->getPathInfo(), $u);
                    if (strpos(Yii::app()->request->getPathInfo(), $u) === false) {
                        $disable = false;
                    } else {
                        $disable = true;
                    }
                }

                if (!$disable) {
                    Yii::app()->controllerMap['license'] = 'app.maintenance.MaintenanceController';
                    Yii::app()->catchAllRequest = array('license/license', array('message' => $data['message']));
                }
            }
        }else{
            LicenseCMS::run()->writeLicenseCache();
            LicenseCMS::run()->writeDataCache();
        }
    }


}
