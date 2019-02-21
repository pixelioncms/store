<?php

/**
 * TranslateBehavior implements the basic methods
 * for translating dynamic content of models.
 * Behavior takes language from SLanguageManager::active or you
 * can specify language id thru language() method.
 *
 * Example:
 * Find object with language id 2
 *     Object::model()->language(2)->find();
 * Detect language from array
 *     Object::model()->language($_GET)->find();
 * Language detected automatically
 *     Object::model()->find();
 *
 * Usage:
 * 1. Create new relation
 *  'translate'=>array(self::HAS_ONE, 'Translate Storage Model', 'object_id'),
 * 2. Attach behavior and enter translateable attributes
 *   'TranslateBehavior'=>array(
 *       'class'=>'ext.behaviors.TranslateBehavior',
 *       'translateAttributes'=>array(
 *           'title',
 *           'short_description',
 *           'full_description'
 *           etc...
 *       ),
 *   ),
 * 3. Set Model::$translateModelName - name of the model that handles translations.
 * 4. Create new db table to handle translated attribute values.
 *    Basic structure: id, object_id, language_id + attributes.
 * 5. Create 'Translate Storage Model' class and set $tableName.
 * 6. Connect events onCreate and onDelete
 * 7. Add language method to admin controller
 */

Yii::import('mod.admin.components.yandexTranslate');

class TranslateBehavior extends CActiveRecordBehavior {

    /**
     * @var array Model attributes available for translate
     */
    public $translateAttributes = array();

    /**
     * @var string
     */
    public $relationName = 'translate';

    /**
     * @var bool Disable yii model events. When disabled, behaviour
     */
    public $disableEvents = false;

    /**
     * @var integer Language id used to load model translation data.
     * If null active language id we'll be used.
     */
    private $_translation_lang;

    /**
     * @param $owner
     */
    public function attach($owner) {
        //$this->disableEvents = (count(Yii::app()->languageManager->getLanguages()) === 1);
        return parent::attach($owner);
    }

    /**
     * Merge object query with translate query.
     */
    public function beforeFind($event) {
        if (!$this->disableEvents)
            $this->applyTranslateCriteria();
        return true;
    }

    /**
     * @return ActiveRecord
     */
    public function applyTranslateCriteria() {
       // die($this->getTranslateLanguageId());
        $cr = $this->owner->getDbCriteria();
        $cr->mergeWith(array(
            'with' => array($this->relationName => array(
                    'condition' => $this->relationName . '.language_id=:lid',
                    'params' => array(
                        ':lid' => $this->getTranslateLanguageId()//
                    )
            )),
        ));
        return $this->owner;
    }

    /**
     * Apply object translation
     */
    public function afterFind($event) {
        if (!$this->disableEvents)
            $this->applyTranslation();
        return parent::afterFind($event);
    }

    /**
     * Apply translated attrs
     */
    public function applyTranslation() {
        $relation = $this->relationName;
        if ($this->owner->$relation) {
            foreach ($this->translateAttributes as $attr)
                $this->owner->$attr = $this->owner->$relation->$attr;
        }
    }

    /**
     * Update model translations
     */
    public function afterSave($event) {
        if ($this->disableEvents)
            return true;

        $relation = $this->relationName;
        $translate = $this->owner->$relation;

        if ($this->owner->isNewRecord || !$translate)
            $this->insertTranslations();
        else
            $this->updateTranslation($translate);
        return true;
    }

    /**
     * Delete model related translations
     */
    public function afterDelete($event) {

        if ($this->disableEvents)
            return true;

        $className = $this->owner->translateModelName;
        $className::model()->deleteAll('object_id=:id', array(
            ':id' => $this->owner->getPrimaryKey()
        ));
        return true;
    }

    /**
     * Create new object translation for each language.
     * Used on creating new object.
     */
    public function insertTranslations() {
        foreach (Yii::app()->languageManager->languages as $lang)
            $this->createTranslation($lang);
    }

    /**
     * Create object translation
     * @param int $languageId Language id
     * @return boolean Translation save result
     */
    public function createTranslation($language) {
        $languageId = $language->id;
        $className = $this->owner->translateModelName;
        $translate = new $className;
        $translate->object_id = $this->owner->getPrimaryKey();
        if(Yii::app()->languageManager->default->code == $language->code && true){
            $translate->language_id = $languageId;
            foreach ($this->translateAttributes as $attr)
                $translate->$attr = $this->owner->$attr;
        }else{
            $api = new yandexTranslate;
            $translate->language_id = $languageId;
            // Populate translated attributes
            foreach ($this->translateAttributes as $attr){
                $data = $api->translate(array(Yii::app()->languageManager->default->code,$language->code),$this->owner->$attr);

                $translate->{$attr} = (isset($data['text']))?$data['text'][0]:$this->owner->$attr;
            }
        }


        return $translate->save(false,false,false);
    }

    /**
     * Update "current" translation object
     * @param ActiveRecord $translate
     */
    public function updateTranslation($translate) {
        foreach ($this->translateAttributes as $attr)
            $translate->$attr = $this->owner->$attr;
        $translate->save(false,false,false);
    }

    /**
     * Get language id to load translated data.
     * @return integer Language id
     */
    public function getTranslateLanguageId() {
        if ($this->_translation_lang)
            return $this->_translation_lang;
        return Yii::app()->languageManager->active->id;
    }

    /**
     * Scope to load translation by language id
     * @param mixed $language or array containing `lang_id` key
     * @return ActiveRecord|mixed
     */
    public function language($language = null) {
        if (is_array($language) && isset($language['lang_id']))
            $language = $language['lang_id'];

        if (!Yii::app()->languageManager->getById($language)){
            $language = Yii::app()->languageManager->active->id;
        }

        $this->_translation_lang = $language;
        return $this->owner;
    }

    /**
     * @return array
     */
    public function getTranslateAttributes() {
        return $this->translateAttributes;
    }

}