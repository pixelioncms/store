<?php

class NotifyController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('CartModule.admin', 'NOTIFIER');
        
        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => array('/admin/cart'),
            $this->pageName
        );
        $this->icon = $this->module->adminMenu['orders']['items'][6]['icon'];
        $this->topButtons = array(
            array(
                'label' => 'Отправить новые товары всем подписчикам',
                'url' => $this->createUrl('delivery'),
                'htmlOptions' => array('class' => 'btn btn-success')
            )
        );
        $this->render('index', array('dataProvider' => ProductNotifications::model()->search()));
    }

    public function actionDelivery() {
        $this->pageName = Yii::t('app', 'Сегодняшние товары');
        
        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => array('/admin/cart'),
            Yii::t('CartModule.admin', 'NOTIFIER') => array('/admin/cart/notify'),
            $this->pageName
        );
        $this->icon = $this->module->adminMenu['orders']['items'][6]['icon'];
        $model = new ShopProduct('search');
        $dataProvider = $model->search(array('today' => true));
        $this->render('delivery', array('model' => $model, 'dataProvider' => $dataProvider));
    }

    public function actionDeliverySend() {
        Yii::app()->request->enableCsrfValidation = false;
        $model = new ShopProduct('search');
        $data = $model->search(array('today' => true))->getData();
        $config = Yii::app()->settings->get('app');
        $host = $_SERVER['HTTP_HOST'];
        $thStyle = 'border-color:#D8D8D8; border-width:1px; border-style:solid;';
        $tdStyle = 'border-color:#D8D8D8; border-width:1px; border-style:solid;';
        $currency = Yii::app()->currency->active->symbol;
    
        
        
        $tables = '<table border="0" width="600px" cellspacing="1" cellpadding="5" style="border-spacing: 0;border-collapse: collapse;">'; //border-collapse:collapse;
        $tables .= '<tr>';
        $tables .= '<th style="' . $thStyle . '">Изображение</th><th style="' . $thStyle . '">Товар</th><th style="' . $thStyle . '">Производитель</th><th style="' . $thStyle . '">Цена за шт.</th>';
        $tables .= '</tr>';
        foreach ($data as $row) {
            $tables .= '<tr>
            <td style="' . $tdStyle . '" align="center"><a href="' . $row->absoluteUrl . '"  target="_blank"><img border="0" src="http://' . $host . '/' . $row->getImageUrl("200x200") . '" alt="' . $row->name . '" /></a></td>
            <td style="' . $tdStyle . '"><a href="' . $row->absoluteUrl . '"  target="_blank">' . $row->name . '</a></td>
            <td style="' . $tdStyle . '" align="center" class="footer">' . $row->manufacturer->name . '</td>
            <td style="' . $tdStyle . '" align="center">' . $row->price.' '.$currency.'</td>
            </tr>';
        }
        $tables .= '</table>';
        
        $theme = Yii::t('CartModule.admin', '{site_name} Новое поступление', array('{site_name}' => $config['site_name']));
        $body = '
<html>
<body>

Здравствуйте!<br />
<p>
    Магазин <b>"' . $config['site_name'] . '"</b> уведомляет Вас о том, что появилось новое поступление.
</p>
' . $tables . '
<p>Будем рады обслужить Вас и ответить на любые вопросы!</p>
</body>
</html>
';



        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $host;
        $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
        $mailer->Subject = $theme;
        $mailer->Body = $body;
        foreach(DeliveryModule::getAllDelivery() as $mail){
             $mailer->AddAddress($mail);
        }
        $mailer->AddReplyTo('noreply@' . $host);
        $mailer->isHtml(true);
        if(!$mailer->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mailer->ErrorInfo;
} else {
    echo 'Message has been sent';
                    $this->setFlashMessage(Yii::t('app', 'Письма успешно отправлены'));
    $this->redirect(array('delivery'));
}
        //$mailer->ClearAddresses();
        //   }
        // }
    }

    /**
     * Send emails
     */
    public function actionSend() {
        $lang = Yii::app()->language;
        $record = ProductNotifications::model()->findAllByAttributes(array('product_id' => $_GET['product_id']));
        $siteName = Yii::app()->settings->get('app', 'site_mame');
        $host = $_SERVER['HTTP_HOST'];

        foreach ($record as $row) {
            if (!$row->product)
                continue;

            $theme = Yii::t('CartModule.admin', '{site_name} уведомляет о наличии интересующего Вас продукта', array(
                        '{site_name}' => $siteName
                    ));
            $body = '
<html>
<body>
Здравствуйте!<br />
<p>
    Магазин <?=$siteName?> уведомляет Вас о том,
    что появился в наличии продукт <a href="' . $row->product->absoluteUrl . '">' . $row->product->name . '</a>.
</p>
<p>Будем рады обслужить Вас и ответить на любые вопросы!</p>
</body>
</html>    
';
            $mailer = Yii::app()->mail;
            $mailer->From = 'robot@' . $host;
            $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
            $mailer->Subject = $theme;
            $mailer->Body = $body;
            $mailer->AddAddress($row->email);
            $mailer->AddReplyTo('robot@' . $host);
            $mailer->isHtml(true);
            $mailer->Send();
            $mailer->ClearAddresses();

            $row->delete();
        }

        $this->setFlashMessage(Yii::t('CartModule.admin', 'Сообщения успешно отправлены.'));
        $this->redirect('index');
    }

}