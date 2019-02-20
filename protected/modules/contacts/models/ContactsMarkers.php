<?php

/**
 * @package modules.contacts.models
 */
class ContactsMarkers extends ActiveRecord {

    const UPLOAD_PATH = 'webroot.uploads';

    public function overviewImage() {
        $html = '';
        $html .= '<div class="input-group"><span class="input-group-addon">@</span><span class="input-group-addon">@</span></div>';
        
        
        
        if (!$this->isNewRecord) {
            if ($this->hasIcon()) {
                return $html.'<div class="img-thumbnail image-box">'.Html::image('/uploads/' . $this->icon_file, $this->name,array('class'=>'')).' '.Html::link('<i class="icon-delete"></i>',array('/admin/contacts/markers/deletefile','id'=>$this->id),array('class'=>'remove')).'</div>';
            }
        }
    }

    const MODULE_ID = 'contacts';

    public $icon_file;

    public function getImageUrl() {
        return '/uploads/' . $this->icon_file;
    }

    public function getImageSize() {
        if ($this->hasIcon()) {
            return getimagesize(Yii::getPathOfAlias(self::UPLOAD_PATH) . '/' . $this->icon_file);
        }
    }

    public function hasIcon() {
        if (isset($this->icon_file)) {
            if (file_exists(Yii::getPathOfAlias(self::UPLOAD_PATH) . '/' . $this->icon_file)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getForm() {

        Yii::import('ext.bootstrap.fileinput.FileInput');
        Yii::import('ext.tinymce.TinymceArea');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'general' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'coords' => array('type' => 'text'),
                        'name' => array('type' => 'text'),
                        'map_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ContactsMaps::model()->findAll(), 'id', 'name'),
                        ),
                        'balloon_content_body' => array('type' => 'TinymceArea'),
                        'icon_content' => array('type' => 'text'),
                        'hint_content' => array('type' => 'text'),
                    ),
                ),
                'my_icon' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_MY_ICON'),
                    'elements' => array(
                        'icon_file' => array(
                            'type' => 'FileInput',
                            'hint' => $this->overviewImage()
                        ),
                        'icon_file_offset_x' => array('type' => 'text'),
                        'icon_file_offset_y' => array('type' => 'text'),
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


    public function tableName() {
        return '{{contacts_markers}}';
    }

    public function rules() {
        return array(
            array('map_id, coords, name', 'required'),
            array('map_id', 'numerical', 'integerOnly' => true),
            array('icon_file', 'FileValidator',
                'types' => 'png',
                'allowEmpty' => true,
                'maxSize' => 10000
            ),
            array('coords, name, icon_file, icon_file_offset_x, icon_file_offset_y, balloon_content_body, hint_content, icon_content', 'type', 'type' => 'string'),
            array('coords', 'match', 'not' => false, 'pattern' => '/\d+(\.\d+)?,\d+(\.\d+)?/'),
        );
    }

    public function beforeSave() {
        $coord = explode(',', $this->coords);
        $this->coords = new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $coord[0] . ' ' . $coord[1] . ')'));
                if(empty($this->icon_file)){
            $this->icon_file = new CDbExpression('NULL');
        }
        return parent::beforeSave();
    }

    protected function beforeFind() {
        parent::beforeFind();
        $alias = $this->getTableAlias(true);
        $criteria = new CDbCriteria;
        $criteria->select = array(
            "*",
            new CDbExpression("CONCAT(X({$alias}.`coords`),',',Y({$alias}.`coords`)) AS coords"),
        );
        $this->dbCriteria->mergeWith($criteria);
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('map_id', $this->map_id);
        $criteria->compare('coords', $this->coords);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('balloon_content_body', $this->balloon_content_body, true);
        $criteria->compare('balloon_content_footer', $this->balloon_content_footer, true);
        $criteria->compare('icon_content', $this->icon_content, true);
        $criteria->compare('hint_content', $this->hint_content, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function afterDelete() {
        $path = Yii::getPathOfAlias(self::UPLOAD_PATH);
        if (file_exists($path . DS . $this->icon_file)) {
            unlink($path . DS . $this->icon_file);
        }
        parent::afterDelete();
    }

}
