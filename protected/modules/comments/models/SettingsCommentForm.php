<?php

class SettingsCommentForm extends FormSettingsModel
{

    public $pagenum;
    public $allow_add;
    public $allow_view;
    public $flood_time;
    public $reply;
    public $control_timeout;

    public static function defaultSettings()
    {
        return array(
            'pagenum' => 5,
            'flood_time' => 10,
            'allow_add' => 0,
            'allow_view' => 0,
            'control_timeout' => ''
        );
    }

    public function getForm()
    {
        $tab = new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'pagenum' => array('type' => 'text'),
                'flood_time' => array('type' => 'text'),
                'control_timeout' => array('type' => 'text'),
                'reply' => array('type' => 'checkbox'),
                'allow_add' => array(
                    'type' => 'dropdownlist',
                    'items' => Yii::app()->access->dataList()
                ),
                'allow_view' => array(
                    'type' => 'dropdownlist',
                    'items' => Yii::app()->access->dataList()
                )

            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        ), $this);
        return $tab;
    }

    public function init()
    {
        $list = array();
        $param = Yii::app()->settings->get('comments');
        if (isset($param->control_timeout))
            $list['control_timeout'] = $param->control_timeout / 60;
        $this->attributes = CMap::mergeArray((array)$param, $list);
    }

    public function rules()
    {
        return array(
            array('pagenum, flood_time, allow_add, allow_view, control_timeout', 'required'),
            //array('bad_name, bad_email', 'length', 'max' => 255),
            array('reply', 'boolean'),
            array('pagenum', 'numerical', 'integerOnly' => true),
        );
    }

    public function save($message = true)
    {
        $this->control_timeout = $_POST['SettingsCommentForm']['control_timeout'] * 60;
        Yii::app()->settings->set('comments', $this->attributes);
        parent::save($message);
    }

}
