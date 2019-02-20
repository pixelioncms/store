<?php

/**
 *
 * @copyright (c) 2018, Semenov Andrew
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @author Semenov Andrew <info@andrix.com.ua>
 *
 * @link http://pixelion.com.ua PIXELION CMS
 * @link http://andrix.com.ua Developer
 *
 */
class NotificationModel extends ActiveRecord
{

    const MODULE_ID = 'admin';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{notifications}}';
    }

    public function getForm()
    {
        Yii::import('ext.tinymce.TinymceArea');
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'name' => array('type' => 'text'),
                'content' => array('type' => 'TinymceArea'),
                'widget' => array(
                    'type' => 'dropdownlist',
                    'items' => Yii::app()->widgets->getData(),
                    'empty' => Yii::t('app', 'EMPTY_LIST'),
                    'hint' => self::t('HINT_WIDGET')
                ),
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

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('date_create'),
        );
        return CMap::mergeArray($a, parent::behaviors());
    }


    public function rules()
    {
        return array(
            array('type, data', 'required'),
            //  array('switch', 'numerical', 'integerOnly' => true),
            //  array('content', 'type', 'type' => 'string'),
            // array('modules, widget, expire, action', 'length', 'max' => 250),
            array('type, status', 'safe', 'on' => 'search'),
        );
    }

    public function getIconName()
    {
        if ($this->type == 'comment') {
            return 'comments';
        } elseif ($this->type == 'order') {
            return 'shopcart';
        } elseif ($this->type == 'callback') {
            return 'phone';
        }
    }

    public function getMessage()
    {

     //   $data = json_decode($this->data, true);

        if ($this->type == 'comment') {
            // return Yii::t('app', 'NOTIFY_COMMENT', array('{param1}' => $data['param1'], '{param2}' => $data['param2']));
        } elseif ($this->type == 'order') {
            // return Yii::t('app', 'NOTIFY_ORDER', array('{param1}' => $data['param1'], '{param2}' => $data['param2']));
        } elseif ($this->type == 'callback') {
            // return Yii::t('app', 'NOTIFY_CALLBACK', array('{param1}' => $data['param1']));
        }
    }

    public function afterSave()
    {
        $dataJson = json_decode(utf8_encode($this->data), true);
        if ($this->type == 'comment') {
            $data = array('message' => array(
                "notification" => array(
                    'id' => $this->id,
                    'vibrate' => true,
                    //'action' => $this->type,
                    "title" => 'dasdasdas',
                    "text" => "на номер " . $dataJson['phone'],
                    "phone" => $dataJson['phone'],
                    // 'summaryText' => 'номер звонка: ' . $dataJson['param1'],
                    'summaryText' => '',
                    'lines' => array($dataJson['phone']),
                )));
        } elseif ($this->type == 'order') {
            $data = array('message' => array(
                "notification" => array(
                    'id' => $this->id,
                    'vibrate' => true,
                    //'action' => $this->type,
                    "title" => 'Новый заказ № 555',
                    "text" => $dataJson['count'] . ' товара на сумму ' . $dataJson['total'],
                    "phone" => (isset( $dataJson['phone']))?$dataJson['phone']:'',
                    'summaryText' => $dataJson['count'] . ' товара на сумму ' . $dataJson['total'],
                    'smallIcon' => 'https://pixelion.com.ua/uploads/android-notify.png', //не реализовано
                    'lines' => array(
                        'Доставка: ' . $dataJson['delivery'],
                        'Оплата: ' . $dataJson['payment'],
                        $dataJson['user_name'],
                        $dataJson['user_email'],
                        $dataJson['user_phone']
                    ),
                    'actions' => array(
                        array(
                            'name' => 'call',
                            'label' => 'Call',
                            //'iconUrl'=>'https://pixelion.com.ua/uploads/android-notify.png' //не реализовано
                        ),
                        /* array(
                             'name'=>'view',
                             'label'=>'View',
                             //'iconUrl'=>'https://pixelion.com.ua/uploads/android-notify.png'
                         ),
                         array(
                             'name'=>'write',
                             'label'=>'Mail',
                             //'iconUrl'=>'https://pixelion.com.ua/uploads/android-notify.png'
                         )*/
                    )
                )));

        } elseif ($this->type == 'callback') {
            //Yii::import('ext.orderproject.OrderprojectWidget');
            $data = array('message' => array(
                "notification" => array(
                    'id' => $this->id,
                    'vibrate' => true,
                    "title" => Yii::t('OrderprojectWidget.default', 'TITLE'),
                    "text" => "на номер " . $dataJson['phone'],
                    "phone" => $dataJson['phone'],
                    // 'summaryText' => 'номер звонка: ' . $dataJson['param1'],
                    'summaryText' => '',
                    'lines' => array($dataJson['phone']),
                    'actions' => array(
                        array(
                            'name' => 'call',
                            'label' => 'Call',
                            //'iconUrl'=>'https://pixelion.com.ua/uploads/android-notify.png' //не реализовано
                        )
                    )
                )));
        } elseif ($this->type == 'new_project') {
            Yii::import('ext.orderproject.OrderprojectWidget');
            $splittedArray = $this->str_split_unicode($dataJson['text'], 35);
            $data = array('message' => array(
                "notification" => array(
                    'id' => $this->id,
                    'vibrate' => true,
                    "title" => Yii::t('OrderprojectWidget.default', 'TITLE'),
                    "text" => "на номер " . $dataJson['phone'],
                    "phone" => $dataJson['phone'],
                    // 'summaryText' => 'номер звонка: ' . $dataJson['param1'],
                    'summaryText' => '',
                    'lines' => CMap::mergeArray(array($dataJson['name']), $splittedArray),
                    'actions' => array(
                        array(
                            'name' => 'call',
                            'label' => 'Call',
                        ),
                        array(
                            'name' => 'write',
                            'label' => 'Mail',
                            //'iconUrl'=>'https://pixelion.com.ua/uploads/android-notify.png'
                        )
                    )
                )));
        }

        if (Yii::app()->hasModule('android')) {
            Yii::app()->getModule('android')->push($data);
        }

        return parent::afterSave();
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return CMap::mergeArray(array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        ), parent::relations());
    }

    public function str_split_unicode($str, $l = 0)
    {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = trim(mb_substr($str, $i, $l, "UTF-8"));
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.data', $this->data, true);
        $criteria->compare('t.status', $this->status, true);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
