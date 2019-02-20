<?php

class ServiceController extends AdminController {

    public $topButtons = false;
    public $icon = 'icon-operator';
    public function filters() {
        $data = LicenseCMS::run()->getData();

        $users = (isset($data['http_auth'])) ? $data['http_auth'] : array();
        return array(
            array(
                'app.addons.HttpAuthFilter',
                'realm' => 'License auth test2',
                'users' => $users,
            )
        );
    }

    public function actionIndex() {
        $data = LicenseCMS::run()->getData();

        $supportForm = new SupportForm;
        $this->pageName = Yii::t('app', 'SVC');
        $this->breadcrumbs = array(
            $this->pageName
        );
        if (isset($_POST['SupportForm'])) {
            $supportForm->attributes = $_POST['SupportForm'];
            if ($supportForm->validate()) {
                $supportForm->sendMail();
                $this->setFlashMessage(Yii::t('app', 'SUCCESS_MSG_SAND'));
                $this->redirect(array('index'));
            }
        }

        if (!isset($data['proffers'])) {
            $data['proffers'] = array();
        }
        $providerProffers = new CArrayDataProvider($data['proffers'], array(
            'sort' => array(
                'attributes' => array('title'),
                'defaultOrder' => array('title' => false),
            ),
                )
        );


        $this->render('index', array(
            'supportForm' => $supportForm,
            'providerProffers' => $providerProffers
        ));
    }

    public function actionUpgrade() {
        $this->pageName = Yii::t('app', 'UPGRADE');
        $this->breadcrumbs = array(
            Yii::t('app', 'SVC') => array('admin/service'),
            $this->pageName
        );

        //$upgrade = new Upgrade();
        //$upgrade->download();
        //$upgrade->setup();
        $this->render('upgrade', array());
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'UPGRADE_SYS'),
                'url' => array('/admin/app/service/upgrade'),
                'icon' => Html::icon('icon-refresh'),
            ),
        );
    }

}
