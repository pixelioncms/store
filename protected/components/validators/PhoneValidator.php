<?php

/**
 * Валидатор телефона
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package app
 * @subpackage validators
 * @uses PhoneValidator
 */
class PhoneValidator extends CValidator {

    public $match = '/\(\d{3}\)\s\d{3}-\d{2}-\d{2}/';

    protected function validateAttribute($object, $attribute) {
        $value = $object->$attribute;
        if (!preg_match($this->match,$value)) {
            $this->addError($object, $attribute, Yii::t('app', 'ERROR_VALID_PHONE'));
        }
    }

}
