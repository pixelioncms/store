<?php

Yii::import('ext.callback.CallbackForm');

/**
 * CallbackAction class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage callback.actions
 * @uses CAction
 * 
 * @property array $receiverMail Массив e-mails
 */
class CallbackAction extends CAction {

    public $receiverMail = array('info@pixelion.com.ua');

    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            $model = new CallbackForm();
            $sended = false;
            if (isset($_POST['CallbackForm'])) {
                $model->attributes = $_POST['CallbackForm'];
                if ($model->validate()) {
                    $sended = true;
                    $this->sendMessage($model);
                    $model->unsetAttributes();
                }
            }
            $this->controller->render('ext.callback.views._form', array(
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
    private function sendMessage($model) {

        $request = Yii::app()->request;
        $body = '<html>
            <body>
            <p>Телефон: <b>' . $model->phone . '</b></p>
            <p>Дата отправки: <b>' . CMS::date(date('Y-m-d H:i:s'), true, true) . '</b></p>
            <br/>
            <br/>
            <p>IP-address: <b>' . $request->userHostAddress . '</b></p>
            <p>User agent: <b>' . $request->userAgent . '</b></p>
            </body>
            </html>';


        $config = Yii::app()->settings->get('app');
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $request->serverName;
        $mailer->FromName = $config->site_name;
        $mailer->Subject = Yii::t('CallbackWidget.default', 'TITLE');
        $mailer->Body = $body;
        if ($config->admin_email)
            $this->receiverMail = explode(',', $config->admin_email);
        foreach ($this->receiverMail as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->AddReplyTo('noreply@' . $request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

}
