<?php

/**
 * OrderprojectForm class file.
 *
 * @author PIXELION CMS development team <info@pixelion-cms.com>
 * @license http://pixelion.com.ua/license.txt PIXELION CMS License
 * @link http://pixelion.com PIXELION CMS
 * @package ext
 * @subpackage Orderproject
 * @uses FormModel
 *
 * @property string $phone Phone
 */
class OrderprojectForm extends FormModel
{

    public $phone;
    public $name;
    public $text;
    public $email;
    public $rules = null;

    public function init()
    {
        parent::init();

        if (!Yii::app()->user->isGuest)
            $this->phone = Yii::app()->user->phone;
    }

    public function rules()
    {
        $rulesList = array();
        if (Yii::app()->request->serverName == 'pixelion.moscow') {
            $rulesList[] = array('rules', 'required', 'message' => Yii::t('OrderprojectWidget.default', 'FORM_RULES_ERR {attribute}'));
        }

        $rulesList[] = array('phone, name, text, email', 'required');
        $rulesList[] = array('phone', 'length', 'max' => 20, 'min' => 7);
        return $rulesList;
    }

    public function attributeLabels()
    {
        return array(
            'name' => Yii::t('OrderprojectWidget.default', 'FORM_USERNAME'),
            'email' => Yii::t('OrderprojectWidget.default', 'FORM_EMAIL'),
            'phone' => Yii::t('OrderprojectWidget.default', 'FORM_PHONE'),
            'text' => Yii::t('OrderprojectWidget.default', 'FORM_TEXT'),
            'rules' => Yii::t('OrderprojectWidget.default', 'FORM_RULES'),
        );
    }

}
