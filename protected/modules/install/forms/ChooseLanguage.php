<?php

class ChooseLanguage extends FormModel {

    public $lang;

    public function rules() {
        return array(
            array('lang', 'required'),
        );
    }

    public function attributeLabels() {
        return array(
            'lang' => Yii::t('InstallModule.default', 'CHOOSELANG'),
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'showErrorSummary' => true,
            'attributes' => array('id' => __CLASS__),
            'elements' => array(
                'lang' => array(
                    'type' => 'radiolist',
                    'items' => self::getLangs(),
                    'layout' => '{label}<br/>{input}<br/>{error}'
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('InstallModule.default', 'NEXT')
                )
            )
                ), $this);
    }

    public static function getLangs() {
        return array(
            'ru' => 'Русский',
            'en' => 'English',
            'uk' => 'Український'
        );
    }

}
