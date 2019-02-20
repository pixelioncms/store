<?php

class SettingsCartForm extends FormSettingsModel {

    public $order_emails;
    public $tpl_body_user;
    public $tpl_subject_user;
    public $tpl_subject_admin;
    public $tpl_body_admin;
    public $notify_delete_order;
    public $notify_change_status_order;

    public static function defaultSettings() {
        return array(
            'order_emails' => Yii::app()->settings->get('app', 'admin_email'),
            'tpl_body_admin' => '<p><strong>Номер заказ:</strong> #{order_id}</p>
<p><strong>Способ оплаты:</strong> {order_payment_name}</p>
<p><strong>Способ доставки:</strong> {order_delivery_name}</p>
<p><strong>Адрес доставки:</strong> {user_address}</p>
<p>&nbsp;</p>
<p>{list}</p>
<p>&nbsp;</p>
<p>Общая стоимость: <strong>{total_price}</strong> {current_currency}</p>
<p>&nbsp;</p>
<p><strong>Контактные данные:</strong></p>
<p>Имя: {user_name}</p>
<p>Телефон: {user_phone}</p>
<p>E-mail: {user_email}</p>
<p>Адрес: {user_address}</p>
<p>Комментарий: {user_comment}</p>',
            'tpl_body_user' => '<p>Здравствуйте, <strong>{user_name}</strong></p>
<p>Способ доставки: <strong>{order_delivery_name}</strong></p>
<p>Способ оплаты: <strong>{order_payment_name}</strong></p>
<p>&nbsp;</p>
<p>Детали заказа вы можете просмотреть на странице: {link_to_order}</p>
<p><br />{list}</p>
<p>Всего к оплате: {for_payment} {current_currency}</p>
<p><strong>Контактные данные:</strong></p>
<p>Телефон: <a href="tel:{user_phone}">{user_phone}</a></p>',
            'tpl_subject_admin' => 'Новый заказ',
            'tpl_subject_user' => 'Вы оформили заказ #{order_id}',
            'notify_delete_order' => true,
            'notify_change_status_order' => true
        );
    }

    public function getForm() {
        Yii::import('ext.tageditor.TagEditor');
        Yii::import('ext.tinymce.TinymceArea');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'global' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Общие'),
                    'elements' => array(
                        'order_emails' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => 'Добавить почту'
                            ),
                        ),
                        'notify_delete_order' => array('type' => 'checkbox'),
                        'notify_change_status_order' => array('type' => 'checkbox'),
                    )
                ),
                'tpl_mail_user' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Шаблон письма для покупателя'),
                    'elements' => array(
                        'tpl_subject_user' => array('type' => 'text'),
                        'tpl_body_user' => array(
                            'type' => 'TinymceArea',
                            'hint' => Html::link('Документация', 'javascript:open_manual()')
                        ),
                    )
                ),
                'tpl_mail_admin' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Шаблон письма для администратора'),
                    'elements' => array(
                        'tpl_subject_admin' => array('type' => 'text'),
                        'tpl_body_admin' => array(
                            'type' => 'TinymceArea',
                            'hint' => Html::link('Документация', 'javascript:open_manual()')
                        ),
                    )
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

    public function rules() {
        return array(
            array('order_emails', 'required'),
            array('notify_delete_order, notify_change_status_order', 'boolean'),
            array('tpl_body_user, tpl_body_admin, tpl_subject_user, tpl_subject_admin', 'type', 'type' => 'string'),
        );
    }

}
