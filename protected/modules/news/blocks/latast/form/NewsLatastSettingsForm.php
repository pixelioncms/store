<?php

/**
 * Модель настроек блока новости
 */
Yii::import('mod.news.blocks.latast.NewsLatastBlock');

class NewsLatastSettingsForm extends WidgetFormModel {

    public $num;
    public $truncate_title;
    public $truncate_text;

    public static function defaultSettings() {
        return array(
            'num' => 10,
            'truncate_title' => NULL,
            'truncate_text' => NULL,
        );
    }

    /**
     * Настройка прав полей
     * @return array
     */
    public function rules() {
        return array(
            array('num, truncate_title, truncate_text', 'type')
        );
    }

    /**
     * Массив выводимых полей
     * @return array
     */
    public function getForm() {
        Yii::import('mod.news.blocks.latast.NewsLatastWidget');
        return array(
            'attributes' => array(
                'type' => 'form',
            ),
            'elements' => array(
                'num' => array(
                    'label' => Yii::t('NewsLatastWidget.default', 'NUM'),
                    'type' => 'text',
                ),
                'truncate_title' => array(
                    'label' => Yii::t('NewsLatastWidget.default', 'TRUNCATE_TITLE'),
                    'type' => 'text',
                ),
                'truncate_text' => array(
                    'label' => Yii::t('NewsLatastWidget.default', 'TRUNCATE_TEXT'),
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

}
