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
class OrderprojectWidgetForm extends WidgetFormModel
{


    public $email;
    public $email_tpl;

    public function rules()
    {
        return array(
            array('email', 'type'),
            array('email', 'EmailListValidator'),
            array('email_tpl', 'required'),
            //  array('', 'boolean')
        );
    }

    public function getForm()
    {
        Yii::import('ext.tinymce.TinymceArea');
        Yii::import('ext.tageditor.TagEditor');
        Yii::import('ext.blocks.orderproject.OrderprojectWidget');
        return array(
            'type' => 'form',
            'attributes' => array(
                'class' => 'form-horizontal',
                'id' => __CLASS__
            ),
            'elements' => array(
                'email' => array(
                    'label' => 'Почта куда буду приходить уведомления',
                    'type' => 'TagEditor',
                    'options' => array('placeholder' => 'Добавить почту'),
                    'hint' => 'Если почта не указана, то будет братся с основых настройках сайта. <code>' . Yii::app()->settings->get('app', 'admin_email') . '</code>'
                ),
                'email_tpl' => array(
                    'label' => 'Шаблон письма уведомления',
                    'type' => 'TinymceArea',

                ),

            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        );
    }


}
