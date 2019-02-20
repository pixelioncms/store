<?php

class TimelineWidgetForm extends WidgetFormModel {

    public $refresh_interval;
    public $limit;
    public $title;

    public static function defaultSettings() {
        return array(
            'refresh_interval' => 10 * 1000,
            'limit' => 10,
        );
    }

    public function rules() {
        return array(
            array('limit, refresh_interval, title', 'type'),
            array('limit', 'length', 'max' => 3, 'min' => 2),
            array('refresh_interval', 'length', 'max' => 2, 'min' => 1),
            array('limit, refresh_interval', 'numerical', 'integerOnly' => true),
            array('refresh_interval', 'required'),
        );
    }

    public function getForm() {
        return array(
            'type' => 'form',
            'attributes' => array(
                'class' => 'form-horizontal',
                'id' => __CLASS__
            ),
            'elements' => array(
                'title' => array(
                    'label' => 'Заголовок блока',
                    'type' => 'text',
                ),
                'limit' => array(
                    'label' => 'Количество поледних записей.',
                    'type' => 'text',
                ),
                'refresh_interval' => array(
                    'label' => 'Интервал обновление, в сек.',
                    'type' => 'text',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        );
    }

    public function getConfigurationFormHtml($obj) {
        Yii::import('app.blocks_settings.*');
        $className = basename(Yii::getPathOfAlias($obj));
        $param = $this->getSettings($className);
        $this->attributes = $param;
        $this->refresh_interval = $param['refresh_interval'] / 1000;
        $form = new WidgetForm($this->getForm(), $this);
        return $form;
    }

    // public function saveSettings($obj, $postData) {
    //     $this->setSettings($obj, $postData[get_class($this)]);
    // }

    public function setSettings($obj, $data) {
        $className = basename(Yii::getPathOfAlias($obj));
        $data['refresh_interval'] = $_POST['TimelineWidgetForm']['refresh_interval'] * 1000;
        Yii::app()->settings->set($className, $data);
    }

}
