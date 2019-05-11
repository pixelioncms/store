<?php

class MailTplModel extends ActiveRecord {

    const MODULE_ID = 'admin';
    public $setOptions = array();
    public $mails=array();
    public function getForm() {
        Yii::import('ext.tinymce.TinymceArea');
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'elements' => array(
                        'subject' => array(
                            'type' => 'text',
                        ),
                        'body' => array('type' => 'TinymceArea'),
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
        return '{{mail_tpl}}';
    }

    public function rules() {
        return array(
            array('body, subject', 'required'),
            // array('user_id', 'numerical', 'integerOnly' => true),
            array('body, subject', 'type', 'type' => 'string'),
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

    public function send(){
        $host= Yii::app()->request->serverName;
        $mailer = Yii::app()->mail;
        $mailer->From = 'robot@' . $host;
        $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
        $mailer->Subject = $this->getSubject();
        $mailer->Body = Yii::app()->controller->renderPartial('mod.admin.views.admin.mailtpl.test.emailcampaignmonitor',array('content'=>$this->getBody()),true,false);
       // echo  $mailer->Body;die;

        $mailer->AddAddress($this->mails);
        $mailer->AddReplyTo('robot@' . $host);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }
    public function getSubject() {
        $array = array(
            '{current_date}' => date('Y-m-d'),
            '{current_time}' => date('H:i:s'),
        );
        //$text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($this->body))), 255, '<br>', false)), 0, 1));
        $text = $this->subject;
        $options = CMap::mergeArray($array, $this->setOptions);

        return CMS::textReplace($text, $options);
    }
    public function getBody() {
        $array = array(
            '{current_date}' => date('Y-m-d'),
            '{current_time}' => date('H:i:s'),
        );
        //$text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($this->body))), 255, '<br>', false)), 0, 1));
        $text = $this->body;
        $options = CMap::mergeArray($array, $this->setOptions);

        return CMS::textReplace($text, $options);
    }

    public function getModelCriteria(CDbCriteria $criteria, $model = false) {
        $result=array();
        if ($model) {
            $r = $model::model()->find($criteria);
            foreach ($r->getAttributes() as $attrname => $attrvalue) {
                $result['{' . strtoupper($attrname) . '}'] = $attrvalue;
            }

        }
        return $result;
    }

    public function getModelByPk($pk, $model = false) {
        $result = array();
        if ($model) {
            $r = $model::model()->findByPk($pk);
            foreach ($r->getAttributes() as $attrname => $attrvalue) {
                $result['{' . strtoupper($attrname) . '}'] = $attrvalue;
            }

        }
        return $result;
    }

}