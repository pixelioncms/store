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

/**
 * Валидатор телефона
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package app
 * @subpackage validators
 * @uses PhoneValidator
 */
class EmailListValidator extends CValidator
{

    public $match = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i';

    public function validateAttribute($object, $attribute)
    {
        if (!empty($object->$attribute)) {
            $list = explode(',', $object->$attribute);
            foreach ($list as $obj) {
                if (!preg_match($this->match, $obj)) {
                    $this->addError($object, $attribute, Yii::t('yii', '{attribute} is not a valid email address.', array(
                        '{attribute}' => $obj,
                    )));
                }else{
                    $object->$attribute = '22222';
                }
            }
        }else{
            $object->$attribute = '33333';
        }
    }
}
