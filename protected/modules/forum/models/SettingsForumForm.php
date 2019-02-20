<?php

class SettingsForumForm extends FormSettingsModel {


    public $pagenum;
    public $edit_post_time;
    public $enable_post_delete;
    public $enable_guest_addtopic;
    public $enable_guest_addpost;
    

    public static function defaultSettings() {
        return array(
            'pagenum' => 10,
            'edit_post_time'=>5,
            'enable_post_delete'=>false,
            'enable_guest_addtopic'=>false,
            'enable_guest_addpost'=>false,
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'pagenum' => array('type' => 'text'),
                'edit_post_time' => array('type' => 'text'),
                'enable_post_delete' => array('type' => 'checkbox'),
                'enable_guest_addtopic' => array('type' => 'checkbox'),
                'enable_guest_addpost' => array('type' => 'checkbox'),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public function rules() {
        return array(
            array('pagenum, edit_post_time', 'required'),
            array('enable_post_delete, enable_guest_addpost, enable_guest_addtopic', 'boolean'),
            array('pagenum, edit_post_time', 'numerical', 'integerOnly' => true),
        );
    }

}
