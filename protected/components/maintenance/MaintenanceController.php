<?php

class MaintenanceController extends ExtController
{

    public $layouts = 'coming';

    public function actionBannedip()
    {
        $component = Yii::app()->getComponent('bannedip');
        $c = $component->params;

        $result = array();
        if ($c['permanently']) {
            $result['content'] = Yii::t('app', 'IP_BANNED_MESSAGE_PERM', array(
                '{reason}' => $c['reason'],
            ));
        } else {
            $result['content'] = Yii::t('app', 'IP_BANNED_MESSAGE', array(
                '{banned_time}' => $c['banned_time'],
                '{left_time}' => $c['left_time'],
                '{reason}' => $c['reason'],
            ));
        }

        $result['title'] = Yii::t('app', 'IP_BANNED_TITLE', array('{ip}' => $component->userIP));
        $this->renderPartial($this->layouts, $result, false, true);
    }

    public function actionSiteclose()
    {
        $result = array();
        $result['content'] = Yii::app()->settings->get('app', 'site_close_text');
        $result['title'] = Yii::app()->settings->get('app', 'site_name');
        $this->layout = 'coming';

        $theme = Yii::app()->theme->getName();
        $layouts = array(
            "webroot.themes.{$theme}.views.layouts",
            "app.maintenance.layouts",
        );

        foreach ($layouts as $layout) {
            if (file_exists(Yii::getPathOfAlias($layout) . DS . $this->layout . '.php')) {
                //$this->render($layout . '.' . $this->layout, array());
                $this->renderPartial($layout . '.' . $this->layout, $result, false, true);
                Yii::app()->end();
            }
        }


    }

    public function actionLicense()
    {
        $result = array();

        $result['content'] = $_GET[0]['message'];
        $result['title'] = Yii::app()->settings->get('app', 'sss');
        $this->renderPartial('app.maintenance.layouts.alert', $result, false, true);
    }

}
