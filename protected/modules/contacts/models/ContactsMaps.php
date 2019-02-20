<?php

/**
 *
 * AIzaSyDyLBWKHAoSUP4lo2Dzh8TEcEdpEXcIB-s
 *
 *
 * @package modules.contacts.models
 */
class ContactsMaps extends ActiveRecord {

    const MODULE_ID = 'contacts';

    public function getForm() {
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'general' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'api_key' => array('type' => 'text'),
                        'name' => array('type' => 'text'),
                        'zoom' => array('type' => 'dropdownlist', 'items' => self::getZoomList()),
                        'center' => array('type' => 'text', 'hint' => self::t('HINT_CENTER')),
                        'width' => array('type' => 'text'),
                        'height' => array('type' => 'text'),
                        //'grayscale'=>array('type' => 'checkbox'),
                        'night_mode' => array('type' => 'checkbox'),
                        'drag' => array('type' => 'checkbox', 'hint' => 'при разрешение меньше 768px отлючается перемещение!'),
                        'scrollwheel' => array('type' => 'checkbox'),
                        'trafficLayer' => array('type' => 'checkbox'),
                        'transitLayer' => array('type' => 'checkbox','hint'=>'Карта не должна иметь тип (satellite)'),
                        'auto_show_routers' => array('type' => 'checkbox'),
                        'type' => array('type' => 'dropdownlist', 'items' => self::getTypeMapList()),
                    ),
                ),
                'panels' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_PANELS'),
                    'elements' => array(
                        'mapTypeControl' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getControlPositions(),
                            'labelHelp' => self::t('HINT_POS'),
                            'empty' => '-- Откл. ---'
                        ),
                        'fullscreenControl' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getControlPositions(),
                            'labelHelp' => self::t('HINT_POS'),
                            'empty' => '-- Откл. ---'
                        ),
                        'zoomControl' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getControlPositions(),
                            'labelHelp' => self::t('HINT_POS'),
                            'empty' => '-- Откл. ---'
                        ),
                        'streetViewControl' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getControlPositions(),
                            'labelHelp' => self::t('HINT_POS'),
                            'empty' => '-- Откл. ---'
                        ),
                        'scaleControl' => array('type' => 'checkbox'),
                        'rotateControl' => array('type' => 'checkbox'),

                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public static function getZoomList() {
        $result = array();
        foreach (range(1, 19) as $num) {
            $result[$num] = $num;
        }
        return $result;
    }

    public static function getTypeMapList() {
        return array(
            'roadmap' => self::t('TYPE_ROADMAP'),
            'satellite' => self::t('TYPE_SATELLITE'),
            'hybrid' => self::t('TYPE_HYBRID'),
            'terrain' => self::t('TYPE_TERRAIN'),
        );
    }

    public static function getControlPositions() {
        return array(
            1 => 'TOP_CENTER',
            2 => 'TOP_LEFT',
            3 => 'TOP_RIGHT',
            4 => 'LEFT_TOP',
            5 => 'LEFT_CENTER',
            6 => 'LEFT_BOTTOM',
            7 => 'RIGHT_TOP',
            8 => 'RIGHT_CENTER',
            9 => 'RIGHT_BOTTOM',
            10 => 'BOTTOM_CENTER',
            11 => 'BOTTOM_LEFT',
            12 => 'BOTTOM_RIGHT',
        );
    }


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{contacts_maps}}';
    }

    public function relations() {
        return array(
            'markers' => array(self::HAS_MANY, 'ContactsMarkers', 'map_id'),
            'routers' => array(self::HAS_MANY, 'ContactsRouter', 'map_id'),
        );
    }

    public function rules() {
        return array(
            array('name, width, height, zoom, center, type, drag, api_key', 'required'),
            array('zoomControl, fullscreenControl, streetViewControl, mapTypeControl, scaleControl, rotateControl', 'numerical', 'integerOnly' => true),
            array('drag, auto_show_routers, scrollwheel, trafficLayer, transitLayer, night_mode, grayscale', 'boolean'),
            array('name', 'length', 'max' => 255),
            array('name', 'type', 'type' => 'string'),
            array('center', 'match', 'not' => false, 'pattern' => '/\d+(\.\d+)?,\d+(\.\d+)?/'),
        );
    }

    public function beforeSave() {
        $coords = explode(',', $this->center);
        $this->center = new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $coords[0] . ' ' . $coords[1] . ')'));

        return parent::beforeSave();
    }

    protected function beforeFind() {
        parent::beforeFind();
        $criteria = new CDbCriteria;
        $criteria->select = "*, CONCAT(X(center),',',Y(center)) AS center"; // YOU MUST TYPE ALL OF YOUR TABLE'S COLUMN NAMES HERE
        $this->dbCriteria->mergeWith($criteria);
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
