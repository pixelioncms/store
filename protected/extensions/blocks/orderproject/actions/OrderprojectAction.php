<?php

Yii::import('ext.blocks.orderproject.OrderprojectForm');

/**
 * OrderprojectAction class file.
 *
 * @author PIXELION CMS development team <info@pixelion-cms.com>
 * @license http://pixelion.com.ua/license.txt PIXELION CMS License
 * @link http://pixelion.com PIXELION CMS
 * @package ext
 * @subpackage callback.actions
 * @uses CAction
 *
 */
class OrderprojectAction extends CAction
{

    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $model = new OrderprojectForm();
            $sended = false;
            if (isset($_POST['OrderprojectForm'])) {
                $model->attributes = $_POST['OrderprojectForm'];
                if ($model->validate()) {
                    $sended = true;
                    $this->sendMessage($model);
                    // if (Yii::app()->hasModule('android')) {


                    /* Yii::app()->getModule('android')->push(array('message' => array(
                         "notification" => array(
                             'id' => rand(1, 100),
                             'vibrate' => true,
                             //'action' => 'callback',
                             "title" => Yii::t('OrderprojectWidget.default', 'TITLE'),
                             "text" => "на номер " . $model->phone,
                             "phone" => $model->phone,
                             'summaryText' => 'номер звонка: ' . $model->phone,
                             'lines' => CMap::mergeArray(array($model->name), $splittedArray),
                         ))));*/
                    // }


                    /*Yii::app()->db->createCommand()->insert('{{user_contact_data}}', array(
                        'type' => 'phone',
                        'value' => $model->phone,
                        'name' => $model->name,
                    ));

                    Yii::app()->db->createCommand()->insert('{{user_contact_data}}', array(
                        'type' => 'email',
                        'value' => $model->email,
                        'name' => $model->name,
                    ));*/

                    $model->unsetAttributes();
                }
            }

            $this->controller->renderPartial('ext.blocks.orderproject.views._form', array(
                'model' => $model,
                'sended' => $sended
            ));

        } else {
            throw new CHttpException(403);
        }
    }


    /**
     * Оптравка письма на почту получателей.
     * @param CallbackForm $model
     */
    private function sendMessage($model)
    {

        $request = Yii::app()->request;

        $tpldata = array();
        $tpldata['sender_name'] = $model->name;
        $tpldata['sender_email'] = $model->email;
        $tpldata['sender_message'] = CHtml::encode($model->text);
        $tpldata['sender_phone'] = $model->phone;

        $config = Yii::app()->settings->get('app');
        $configWgt = Yii::app()->settings->get('OrderprojectWidget');
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $request->serverName;
        $mailer->FromName = $config->site_name;
        $mailer->Subject = Yii::t('OrderprojectWidget.default', 'TITLE') . ' ' . Yii::app()->request->serverName;
        $mailer->Body = Html::text(Yii::app()->etpl->template($tpldata, $configWgt->email_tpl));

        if (isset($configWgt) && !empty($configWgt->email)) {
            $receiverMail = explode(',', $configWgt->email);
        } else {
            $receiverMail = array($config->admin_email);
        }

        foreach ($receiverMail as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->AddReplyTo('noreply@' . $request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();


       /* Yii::import('app.notification.NotificationModel');
        $notificationsend = new NotificationModel;
        $notificationsend->type = 'new_project';
        $dsend['phone'] = $model->phone;
        $dsend['text'] = $model->text;
        $dsend['name'] = $model->name;
        $dsend['email'] = $model->email;
        $notificationsend->data = json_encode($dsend);
        $notificationsend->save(false, false, false);*/


    }


}
