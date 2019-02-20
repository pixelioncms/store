<?php

/**
 * @package modules.contacts.models
 */
class ContactsRouter extends ActiveRecord {

    const MODULE_ID = 'contacts';

    /**
     * Multilingual attrs
     */
    public $name;

    /**
     * Name of the translations model.
     */
    public $translateModelName = 'ContactsRouterTranslate';

    public function getForm() {
        Yii::import('ext.colorpicker.ColorPicker');
        Yii::import('ext.tinymce.TinymceArea');
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
                        'name' => array('type' => 'text'),
                        'map_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ContactsMaps::model()->findAll(), 'id', 'name'),
                        ),
                        'preset' => array(
                            'type' => 'text',
                            'hint' => self::t('HINT_PRESET', array(
                                '{link}' => Html::link('https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage/', 'https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage-docpage/', array('target' => '_blank'))
                            ))
                        ),
                        'mapStateAutoApply' => array('type' => 'checkbox'),
                        'color' => array('type' => 'ColorPicker'),
                        'opacity' => array('type' => 'dropdownlist', 'items' => self::getOpacityList(),),
                    ),
                ),
                'start_point' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_START'),
                    'elements' => array(
                        'start_coords' => array('type' => 'text'),
                        'start_icon_content' => array('type' => 'text'),
                        'start_balloon_content_body' => array('type' => 'TinymceArea'),
                        'start_icon_content' => array('type' => 'text'),
                    ),
                ),
                'end_point' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_END'),
                    'elements' => array(
                        'end_coords' => array('type' => 'text'),
                        'end_icon_content' => array('type' => 'text'),
                        'end_balloon_content_body' => array('type' => 'textarea', 'class' => 'editor'),
                        'end_icon_content' => array('type' => 'text'),
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

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOpacityList() {
        return array(
            '0.1' => '10%',
            '0.2' => '20%',
            '0.3' => '30%',
            '0.4' => '40%',
            '0.5' => '50%',
            '0.6' => '60%',
            '0.7' => '70%',
            '0.8' => '80%',
            '0.9' => '90%',
            '1.0' => '100%'
        );
    }

    public function tableName() {
        return '{{contacts_router}}';
    }

    public function getJsonRoute() {
        $scoord = explode(',', $this->start_coords);
        $ecoord = explode(',', $this->end_coords);
        $array = array(
            'start_balloon_content_body' => $this->start_balloon_content_body,
            'end_balloon_content_body' => $this->end_balloon_content_body,
            'start_icon_content' => $this->start_icon_content,
            'end_icon_content' => $this->end_icon_content,
            'color' => $this->color,
            'opacity' => $this->opacity,
            'preset' => $this->preset,
            'mapStateAutoApply' => (bool) $this->mapStateAutoApply,
            'start' => array((float) $ecoord[0], (float) $ecoord[1]),
            'end' => array((float) $scoord[0], (float) $scoord[1]),
        );
        return CJSON::encode($array);
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array(
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'translateAttributes' => array(
                    'name',
                ),
            ),
        );
    }

    public function rules() {
        return array(
            array('map_id, start_coords, end_coords, opacity, color', 'required'),
            array('map_id', 'numerical', 'integerOnly' => true),
            array('color', 'length', 'min' => 7, 'max' => 7),
            array('opacity', 'type', 'type' => 'float'),
            array('mapStateAutoApply', 'boolean'),
            array('start_coords, end_coords, name, color, preset, start_balloon_content_body, end_balloon_content_body, start_icon_content, end_icon_content', 'type', 'type' => 'string'),
            array('start_coords', 'match', 'not' => false, 'pattern' => '/\d+(\.\d+)?,\d+(\.\d+)?/'),
            array('end_coords', 'match', 'not' => false, 'pattern' => '/\d+(\.\d+)?,\d+(\.\d+)?/'),
        );
    }

    public function beforeSave() {
        $scoord = explode(',', $this->start_coords);
        $this->start_coords = new CDbExpression("GeomFromText(:start_point)", array(':start_point' => 'POINT(' . $scoord[0] . ' ' . $scoord[1] . ')'));
        $ecoord = explode(',', $this->end_coords);
        $this->end_coords = new CDbExpression("GeomFromText(:end_point)", array(':end_point' => 'POINT(' . $ecoord[0] . ' ' . $ecoord[1] . ')'));

        return parent::beforeSave();
    }

    protected function beforeFind() {
        parent::beforeFind();
        $alias = $this->getTableAlias(true);
        $criteria = new CDbCriteria;
        $criteria->select = array(
            "*",
            new CDbExpression("CONCAT(X({$alias}.`start_coords`),',',Y({$alias}.`start_coords`)) AS start_coords"),
            new CDbExpression("CONCAT(X({$alias}.`end_coords`),',',Y({$alias}.`end_coords`)) AS end_coords"),
        );
        $this->dbCriteria->mergeWith($criteria);
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->with = array('translate');
        $criteria->compare('id', $this->id);
        $criteria->compare('map_id', $this->map_id);
        $criteria->compare('start_coords', $this->start_coords);
        $criteria->compare('end_coords', $this->end_coords);
        $criteria->compare('translate.name', $this->name, true);
        $criteria->compare('start_balloon_content_body', $this->start_balloon_content_body, true);
        $criteria->compare('end_balloon_content_body', $this->end_balloon_content_body, true);
        $criteria->compare('start_icon_content', $this->start_icon_content, true);


        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
