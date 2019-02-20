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
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?= Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('app', 'site_name'))) ?></title>
        <link rel="shortcut icon" href="<?= $assetsUrl; ?>/images/favicon.ico" type="image/x-icon">
    </head>
    <body class="no-radius">
        <div id="wrapper-tpl">
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="/admin"><span class="hidden-xs hidden-sm">CORNER</span></a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <?php $this->widget('ext.mbmenu.AdminMenu'); ?>
                        </div>
                        <ul class="navbar-right">
                            <?php
                            $notification = NotificationModel::model()->findAll();
                            if ($notification) {
                                ?>
                                <li><?= Html::link(Html::icon('icon-notification') . ' <span data="notification" class="label label-success">2</span>', 'javascript:void(0)', array('target' => '_blank', 'class' => 'dropdown-toggle', 'data-toggle' => "dropdown", 'aria-haspopup' => "false", 'aria-expanded' => "false")) ?>

                                    <div class="dropdown-menu notification-block">
                                        <?php foreach ($notification as $notify) { ?>

                                        <?php } ?>
                                        <div class="notification">
                                            <div class="notification-icon">
                                                <i class="icon-shopcart"></i>
                                            </div>
                                            <div class="notification-content">
                                                <span class="title">Андрей Семенов</span>
                                                <span class="message">Заказал <b>3</b> товара на сумму <b>3 400 грн.</b></span>
                                                <span class="date"><?= CMS::date($notify->date_create, true); ?></span>
                                            </div>
                                        </div>
                                        <div class="notification">
                                            <div class="notification-icon">
                                                <i class="icon-comments"></i>
                                            </div>
                                            <div class="notification-content">
                                                <span class="title">Андрей Семенов</span>
                                                <span class="message">Прокомментировал товар <b>Кроссовки 157</b></span>
                                                <span class="date"><?= CMS::date($notify->date_create, true); ?></span>
                                            </div>
                                        </div>
                                        <a class="btn btn-default btn-block" href="#">Все</a>
                                    </div>

                                </li>
                            <?php } ?>
                            <li><?= Html::link(Html::icon('icon-home'), '/', array('target' => '_blank')) ?></li>
                            <li><?= Html::link(Html::icon('icon-locked'), array('/users/logout')) ?></li>
                            <?php if (Yii::app()->settings->get('app', 'multi_language')) { ?>
                                <li><?php $this->widget('ext.blocks.chooseLanguage.ChooseLanguage', array('skin' => 'dropdown')); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
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
                                    'icon' => Html::icon('icon-menu'),
                                    'linkOptions' => array('id' => 'menu-toggle')
                                )), $asm)
                        ));
                        ?>

                    </div>
                <?php } ?>

                <div id="page-content-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 clearfix module-header pdd">
                                <div class="pull-left">
                                    <h1 class="hidden-xs">
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

                                <div class="pull-right">
                                    <?php $this->renderPartial('mod.admin.views.layouts.inc._topButtons', array()); ?>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <?php $this->renderPartial('mod.admin.views.layouts.inc._breadcrumbs', array()); ?>

                            <div class="pdd">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                        if (Yii::app()->user->hasFlash('error')) {
                                            Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'), false);
                                        }
                                        if (Yii::app()->user->hasFlash('success')) {
                                            Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'), false);
                                        }
                                        ?>

                                        <?php


                                        $this->widget('ext.blocks.accuweather.AccuweatherWidget'); ?>

                                        <?= $content ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <?php
            if (($messages = Yii::app()->user->getFlash('messages'))) {
                echo '<script type="text/javascript">';
                foreach ($messages as $m) {
                    echo "common.notify('" . $m . "', 'success');";
                }
                echo '</script>';
            }

            if (($messages = Yii::app()->user->getFlash('notify'))) {

                echo '<script type="text/javascript">';
                foreach ($messages as $type => $errors) {
                    if (is_array($errors)) {
                        foreach ($errors as $err) {
                            echo "common.notify('{$err}', '{$type}');";
                        }
                    } else {
                        echo "common.notify('{$errors}', '{$type}');";
                    }
                }
                echo '</script>';
            }
            ?>
            <footer class="footer">
                <p class="col-xs-12">
                    {copyright}
                    <br/>
                    <?php
                    //if (YII_DEBUG)
                        echo $this->getPageGen();
                    ?>
                </p>
            </footer>
        </div>
    </body>
</html>
