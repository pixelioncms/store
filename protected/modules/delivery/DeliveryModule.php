<?php

class DeliveryModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
        ));
        $this->setIcon('icon-sentmail');
    }

    public function afterUninstall() {
        Yii::app()->db->createCommand()->dropTable(Delivery::model()->tableName());
        return parent::afterUninstall();
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'visible' => Yii::app()->user->checkAccess('Delivery.Default.*')
                    ),
                ),
            ),
        );
    }

    public static function getAllDelivery() {
        $delivery = Delivery::model()->findAll();
        $mails = array();
        $users = User::model()->subscribe()->findAll();
        if (count($users)) {
            foreach ($users as $user) {
                $mails[] = $user->email;
            }
        }
        if (count($delivery)) {
            foreach ($delivery as $subscriber) {
                $mails[] = $subscriber->email;
            }
        }
        return $mails;
    }

    public function getRules() {
        return array(
            'delivery/' => 'delivery/default/index',
            'delivery/send/' => 'delivery/default/send',
            'delivery/confirmed/<key>' => array('delivery/default/confirmed'),
            'delivery/<action:[.\w]+>' => 'delivery/default/<action>',
            'delivery/<action:[.\w]>/*' => 'delivery/default/<action>',
        );
    }

}
