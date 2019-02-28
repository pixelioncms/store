<?php
$assetsUrl = Yii::app()->getModule('admin')->assetsUrl;
$asm = $this->module->adminSidebarMenu;

$this->renderPartial('mod.admin.views.layouts.inc._cs', array(
    'assetsUrl' => $assetsUrl,
    'baseAssetsUrl' => $this->baseAssetsUrl
));
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="img/png" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">


    <meta charset="<?= Yii::app()->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('app', 'site_name'))) ?></title>
    <link rel="shortcut icon" href="<?= $assetsUrl; ?>/images/favicon.ico" type="image/x-icon">
</head>
<body class="no-radius">

<div id="wrapper-tpl">

    <nav class="navbar navbar-expand-lg fixed-top">

        <a class="navbar-brand" href="/admin"><span class="d-none d-md-block">PIXELION</span></a>

        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar2">
            <span></span>
            <span></span>
            <span></span>
        </button>


        <div id="navbar2" class="collapse navbar-collapse mr-auto">
            <?php $this->widget('ext.mbmenu.AdminMenu'); ?>
        </div>

        <ul class="navbar-right">

            <?php


            //$notificationsend = new NotificationModel;
            /* $notificationsend->type = 'callback';
             $dsend['phone'] = '+3 (555) 555-55-22';
             $notificationsend->data = json_encode($dsend);
             //$notificationsend->save(false, false, false);


             $notificationsend->type = 'comment';
             $dsend['phone'] = '+3 (555) 555-55-22';
             $notificationsend->data = json_encode($dsend);
             //$notificationsend->save(false, false, false);

             $notificationsend->type = 'order';
             $dsend['total'] = '555.10 грн.';
             $dsend['count'] = 3;
             $dsend['user_name'] = 'Семенов Андрей Анатольевич';
             $dsend['user_email'] = 'andrew.panixgmai.com';
             $dsend['user_phone'] = '+3 (555) 555-55-22';
             $dsend['delivery'] = 'Новая почта';
             $dsend['payment'] = 'Наложенный платеж';
             $notificationsend->data = json_encode($dsend);
             $notificationsend->save(false, false, false);

 */
            $notification = NotificationModel::model()->findAll(array(
                'limit' => 3,
                'condition' => '`t`.`status` = 1',
                'order' => 'id DESC'
            ));


            $notificationAll = NotificationModel::model()->count(array(
                'condition' => '`t`.`status` = 1',
            ));

            if ($notification) {

                $notificationLink = Html::link(Html::icon('icon-notification') . ' <span class="badge badge-success" id="notification-count">' . $notificationAll . '</span>', 'javascript:void(0)', array(
                    'class' => 'nav-link dropdown-toggle',
                    'data-toggle' => "dropdown",
                    'id' => 'notifactionLink'
                    //'aria-haspopup' => "false",
                    //'aria-expanded' => "false",
                ));


            } else {
                $notificationLink = Html::link(Html::icon('icon-notification'), array('/admin/app/notification'), array('class' => 'nav-link'));
            }
            ?>
            <li id="notifaction">
                <?= $notificationLink; ?>

                <?php if ($notification) { ?>
                    <div id="notification-dropdown" class="dropdown-menu dropdown-menu-right notification-block">
                        <ul class="list-group list-group-flush ">
                            <?php foreach ($notification as $notify) { ?>
                                <li id="notifaction-<?= $notify->id ?>" class=" list-group-item notification"
                                    data-notification-id="<?= $notify->id ?>">
                                    <div class=" row">
                                        <div class="notification-icon col-sm-3">
                                            <i class="icon-<?= $notify->iconName; ?>"></i>
                                        </div>
                                        <div class="notification-content col-sm-9">
                                            <span class="title">
                                                <?php if ($notify->user) { ?>
                                                    <!--<img class="img-thumbnail" width="32" src="<?= Yii::app()->user->avatarUrl ?>" alt="Андрей Семенов" />-->
                                                    <?= $notify->user->login; ?>
                                                <?php } else { ?>
                                                    гость
                                                <?php } ?>


                                            </span>
                                            <span class="message"><?= $notify->message; ?></span>
                                            <span class="date">
                                                <?= CMS::date($notify->date_create, true); ?>
                                                <div class="float-right">
                                                <i class="icon-android" data-toggle="tooltip"
                                                   title="Отправлено 15 уведомлений на Android"></i>
                                                <i class="icon-apple" data-toggle="tooltip"
                                                   title="Отправлено 3 уведомления на Apple"></i>
                                                    </div>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            <?= Html::link(Yii::t('app', 'NOTIFICATION_COUNT', array('{n}' => $notificationAll)), array('/admin/app/notification'), array('class' => 'btn btn-light btn-block')); ?>

                        </ul>
                    </div>
                <?php } ?>
            </li>

            <li><?= Html::link(Html::icon('icon-home'), '/', array('target' => '_blank', 'class' => 'nav-link')) ?></li>
            <li><?= Html::link(Html::icon('icon-locked'), array('/users/logout'), array('class' => 'nav-link')) ?></li>
            <?php if (Yii::app()->settings->get('app', 'multi_language')) { ?>
                <li class="dropdown">
                    <?php if (count(Yii::app()->languageManager->getLanguages()) > 1) { ?>
                        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-expanded="false">
                            <?= Html::image('/uploads/language/' . Yii::app()->languageManager->active->flag_name, Yii::app()->languageManager->active->name); ?>
                        </a>
                    <?php } else { ?>
                        <a href="javascript:void(0);">
                            <?= Html::image('/uploads/language/' . Yii::app()->languageManager->active->flag_name, Yii::app()->languageManager->active->name); ?>
                        </a>
                    <?php } ?>
                    <?php if (count(Yii::app()->languageManager->getLanguages()) > 1) { ?>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <?php
                            foreach (Yii::app()->languageManager->getLanguages() as $lang) {
                                $classLi = ($lang->code == Yii::app()->language) ? $lang->code . ' active' : $lang->code;
                                $link = ($lang->is_default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();
                                //Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name), $link, array('title' => $lang->name));
                                ?>
                                <li class="nav-item">
                                    <?php
                                    echo Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name) . ' ' . $lang->name, $link, array('class' => 'nav-link'));
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>

    </nav>
    <?php
    $class = '';
    $class .= (!$asm) ? ' full-page' : '';
    if (isset($_COOKIE['wrapper'])) {
        $class .= ($_COOKIE['wrapper'] == 'true') ? ' active' : '';
    }
    ?>
    <div id="wrapper" class="<?= $class ?>">
        <?php if ($asm) { ?>
            <div id="sidebar-wrapper">

                <?php
                $this->widget('mod.admin.components.AdminModuleMenu', array(
                    'htmlOptions' => array('class' => 'sidebar-nav', 'id' => 'menu'),
                    'activeCssClass' => 'active',
                    'lastItemCssClass' => '',
                    'items' => CMap::mergeArray(array(array(
                        'label' => '',
                        'url' => '#',
                        'encodeLabel' => false,
                        'icon' => '',
                        'linkOptions' => array('id' => 'menu-toggle')
                    )), $asm)
                ));
                ?>

            </div>
        <?php } ?>

        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 clearfix module-header">
                        <div class="float-left">
                            <h1 class="d-none d-md-block d-sm-block d-lg-block">
                                <?php
                                if (isset($this->icon)) {
                                    echo $this->icon;
                                } else {
                                    echo Html::icon($this->module->icon);
                                }
                                ?>
                                <?= Html::encode($this->pageName) ?>
                            </h1>
                        </div>

                        <div class="float-right">
                            <?php $this->renderPartial('mod.admin.views.layouts.inc._topButtons', array()); ?>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <?php $this->renderPartial('mod.admin.views.layouts.inc._breadcrumbs', array()); ?>

                    <div class="col-sm-12">


                        <?php
                        if (Yii::app()->params['demo']) {
                            Yii::app()->tpl->alert('info', Yii::t('app', 'DEMO_MESSAGE'), false);
                        }


                        if (Yii::app()->user->hasFlash('error')) {
                            Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'), false);
                        }
                        if (Yii::app()->user->hasFlash('success')) {
                            Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'), false);
                        }

                        if (CMS::checkApp()) {
                            echo Yii::app()->request->userAgent;
                        }


                        /* $users = array();
                          // User 1
                          $users[0]['username'] =  'admin';
                          $users[0]['password'] =  'admin';
                          // User 2
                          $users[1]['username'] =  'user2';
                          $users[1]['password'] =  'password2';
                          // User 3
                          $users[2]['username'] =  'user3';
                          $users[2]['password'] =  'password3';

                          foreach($users as $user => $data)
                          {
                              $username = $data['username'];
                              $password = $data['password'];
                              // Encrypt password
                              $encryptedpwd = CMS::crypt_apr1_md5($password);

                              // Print line to be added to .htpasswd file
                              echo $username . ':' . $encryptedpwd;
                              echo '<br />';
                          }



                          $username2='admin';
                          $password2='admin';

                          $curl = Yii::app()->curl;
                          $curl->options = array(
                              'timeout' => 320,
                              'setOptions' => array(
                                  CURLOPT_HEADER => false,
                                  CURLOPT_USERPWD=>$username2 . ":" . $password2,
                                  CURLOPT_RETURNTRANSFER=>1,
                                 // CURLOPT_SSL_VERIFYPEER => false,
                              ),
                          );
                          $connent = $curl->run('http://pixelion.store.loc/uploads/test', array());

                          if (!$connent->hasErrors()) {
                              $result = CJSON::decode($connent->getData());

                          } else {
                              $error = $connent->getErrors();

                              if ($error->code == 22) {
                                  $result = array(
                                      'status' => 'error',
                                      'message' => $error->message,
                                      'code' => $error->code
                                  );
                              } else {
                                  $result = array(
                                      'status' => 'error',
                                      'message' => $error->message,
                                      'code' => $error->code
                                  );
                              }

                          }
  print_r($result);





                          $username = 'admin';
                          $password = 'admin';

                          // Encrypt password
                          $encrypted_password = crypt($password, base64_encode($password));

                          // Print line to be added to .htpasswd file
                          //echo $username . ':' . $encrypted_password;

                          $htaccess = file_get_contents(Yii::getPathOfAlias('webroot.uploads.test').DS.'.htaccess');
                          $rules= 'test';
                          $htaccess = str_replace('###CUSTOM RULES###', $rules."\n###CUSTOM RULES###", $htaccess);
                          //file_put_contents(Yii::getPathOfAlias('webroot.uploads.test').DS.'.htaccess', $htaccess);
  */
                        ?>
                        <?= $content ?>


                    </div>
                </div>
            </div>

        </div>

    </div>
    <?php

    $cs = Yii::app()->clientScript;
    if (($messages = Yii::app()->user->getFlash('notify'))) {
        foreach ($messages as $type => $errors) {
            if (is_array($errors)) {
                foreach ($errors as $k => $err) {
                    $cs->registerScript('common.notify' . $k, "common.notify('{$err}', '{$type}');", CClientScript::POS_END);
                }
            } else {
                $cs->registerScript('common.notify' . $type, "common.notify('{$errors}', '{$type}');", CClientScript::POS_END);
            }
        }

    }
    ?>

    <footer class="footer">
        <div class="container">
            {copyright}
            <br/>
            <?php
            //if (YII_DEBUG)
            echo $this->getPageGen();
            ?>
        </div>
    </footer>

</div>
</body>
</html>
