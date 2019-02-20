<?php

class NovaposhtaConfigurationModel extends CModel {

    public $apikey;
    public $date;

    public function rules() {
        return array(
            array('apikey, date', 'type')
        );
    }

    public function attributeNames() {
        return array(
            'apikey' => Yii::t('CartModule.delivery', 'NOVAPOSHTA_APIKEY'),
            'date' => Yii::t('CartModule.delivery', 'NOVAPOSHTA_DATE'),
        );
    }

    public function getForm() {
        return array(
            'type' => 'form',
            'elements' => array(
                'apikey' => array(
                    'label' => Yii::t('CartModule.delivery', 'NOVAPOSHTA_APIKEY'),
                    'type' => 'text',

                ),
                'date' => array(
                    'label' => Yii::t('CartModule.delivery', 'NOVAPOSHTA_DATE'),
                    'type' => 'text',

                ),
            )
        );
    }

}
