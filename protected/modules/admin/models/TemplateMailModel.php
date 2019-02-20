<?php

class TemplateMailModel extends ActiveRecord {

    const MODULE_ID = 'admin';
    public $setOptions = array();

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'elements' => array(
                        'header' => array(
                            'type' => 'text',
                        ),
                        'body' => array('type' => 'textarea', 'class' => 'editor'),
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

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{tpl_mail}}';
    }

    public function rules() {
        return array(
            array('body, header', 'required'),
            // array('user_id', 'numerical', 'integerOnly' => true),
            array('body, header', 'type', 'type' => 'string'),
                //array('ip_address', 'length', 'max' => 50),
                // array('ip_address, time, date_create', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('reason', $this->reason, true);
        $criteria->compare('ip_address', $this->ip_address, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('time', $this->time, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getBody() {
        $array = array(
            '%current_date%' => date('Y-m-d'),
            '%current_time%' => date('H:i:s'),
        );

        //$text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($this->body))), 255, '<br>', false)), 0, 1));
        $text = $this->body;
        $results = CMap::mergeArray($array, $this->setOptions);
        foreach ($results as $from => $to) {
            $formResult[] = $from;
            $toResult[] = $to;
        }

        return CMS::textReplace($text, $formResult, $toResult);
    }

    public function getModelCriteria(CDbCriteria $criteria, $model = false) {
        if ($model) {
            $r = $model::model()->find($criteria);
            foreach ($r->getAttributes() as $attrname => $attrvalue) {
                $result['%' . strtoupper($attrname) . '%'] = $attrvalue;
            }
            return $result;
        }
    }

    public function getModelByPk($pk, $model = false) {
        $result = array();
        if ($model) {
            $r = $model::model()->findByPk($pk);
            foreach ($r->getAttributes() as $attrname => $attrvalue) {
                $result['%' . strtoupper($attrname) . '%'] = $attrvalue;
            }
            return $result;
        }
    }

}