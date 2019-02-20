<?php
//$this->widget('ext.admin.sitePanel.PanelWidget');
Yii::import('mod.compare.CompareModule');
Yii::import('mod.wishlist.WishlistModule');
Yii::import('mod.wishlist.models.Wishlist');

?>

    <script>
        $(window).on('load', function () {
            $preloader = $('.loaderArea'),
                $loader = $preloader.find('.loader');
            $loader.fadeOut();
            $preloader.delay(350).fadeOut('slow');
        });
    </script>
<?php
$this->widget('ext.admin.sitePanel.PanelWidget', array());
?>
    <!--ПРЕЛОАДЕР-->
    <div class="loaderArea">
        <div class="loader">
            <div class="cssload-inner cssload-one"></div>
            <div class="cssload-inner cssload-two"></div>
            <div class="cssload-inner cssload-three"></div>
        </div>
    </div>


    <div class="alert alert-info d-none" id="alert-demo" style="margin: 1rem">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="alert-heading">Добро пожаловать!</h5>
        Это демонстрационный сайт, вся информация на сайте вымышленная, предоставлена исключительно для ознакомление с
        функционало сайта.

    </div>
    <header>
        <div id="header-top">
            <div class="container">


                <nav class="navbar-expand">
                    <div class="navbar-collapse">
                        <ul class="nav">


                            <?php
                                Yii::import('mod.pages.models.Page');
                            $pages = Page::model()->published()->findAll();
                            foreach ($pages as $page) { ?>
                                <li class="nav-item"><?= Html::link($page->title, $page->getUrl(), array('class' => 'nav-link')) ?></li>

                            <?php }


                            ?>


                            <li class="nav-item"><?= Html::link(Yii::t('CompareModule.default', 'COMPARE', array('{c}' => CompareProducts::countSession())), array('/compare'), array('class' => 'top-compare nav-link')) ?></li>
                            <li class="nav-item"><?= Html::link(Yii::t('WishlistModule.default', 'WISHLIST', array('{c}' => Wishlist::model()->countBy())), array('/wishlist'), array('class' => 'top-wishlist nav-link')) ?></li>




                        </ul>
                        <ul class="nav ml-auto">

                            <?php if (count(Yii::app()->languageManager->getLanguages()) > 1) { ?>
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="nav-link dropdown-toggle"
                                       data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        Язык: <b><?= Yii::app()->languageManager->active->name ?></b></a>
                                    <div class="dropdown-menu">
                                        <?php

                                        foreach (Yii::app()->languageManager->getLanguages() as $lang) {
                                            $classLi = ($lang->code == Yii::app()->language) ? $lang->code . ' active' : $lang->code;
                                            $link = ($lang->is_default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();
                                            //Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name), $link, array('title' => $lang->name));

                                            echo Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name) . ' ' . $lang->name, $link);


                                        }
                                        ?>
                                    </div>
                                </li>
                            <?php } ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    Валюта: <b><?= Yii::app()->currency->active->iso ?></b>
                                </a>
                                <div class="dropdown-menu">
                                    <?php
                                    foreach (Yii::app()->currency->currencies as $currency) {
                                        echo Html::ajaxLink($currency->iso, '/shop/ajax/activateCurrency/' . $currency->id, array(
                                            'success' => 'js:function(){window.location.reload(true)}',
                                        ), array('id' => 'sw' . $currency->id, 'class' => Yii::app()->currency->active->id === $currency->id ? 'dropdown-item active' : 'dropdown-item'));
                                    }
                                    ?>
                                </div>
                            </li>

                            <?php if (Yii::app()->user->isGuest) { ?>
                                <li class="nav-item">
                                    <?= Html::link(Html::icon('icon-user') . ' ' . Yii::t('common', 'LOG_IN'), array('/users/login'), array('class' => 'nav-link')); ?>

                                </li>
                                <li class="nav-item">
                                    <?= Html::link(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('/users/register'), array('class' => 'nav-link')); ?>
                                </li>
                            <?php } else { ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false"><?= Yii::app()->user->login; ?>
                                    </a>
                                    <div class="dropdown-menu">
                                        <?= Html::link(Html::icon('icon-user') . ' ' . Yii::t('app', 'PROFILE'), array('/users/profile'), array('class' => 'dropdown-item')); ?>
                                        <?= Html::link(Html::icon('icon-shopcart') . ' ' . Yii::t('common', 'MY_ORDERS') . ' <span class="badge badge-success">4</span>', Yii::app()->createUrl('/cart/orders'), array('class' => 'dropdown-item')); ?>

                                        <?php
                                        if (Yii::app()->user->isSuperuser || Yii::app()->user->openAccess(array('Admin.Default.*', 'Admin.Default.Index'))) {
                                            echo '<div class="dropdown-divider"></div>';
                                            echo Html::link(Html::icon('icon-tools') . ' ' . Yii::t('app', 'ADMIN_PANEL'), array('/admin'), array('class' => 'dropdown-item'));
                                            echo '<div class="dropdown-divider"></div>';
                                        }
                                        ?>
                                        <?= Html::link(Html::icon('icon-logout') . ' ' . Yii::t('app', 'LOGOUT'), Yii::app()->createUrl('/users/logout'), array('class' => 'dropdown-item')); ?>

                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php //$this->widget('mod.shop.widgets.currency.CurrencyWidget', array()); ?>
                        <?php //$this->widget('ext.blocks.chooseLanguage.ChooseLanguage', array()); ?>
                        <?php //$this->widget('mod.users.widgets.login.LoginWidget', array()); ?>
                    </div>
                </nav>
            </div>

        </div>
        <div class="container" id="header-center">
            <div class="row">
                <div class="col-lg-3"><a class="navbar-brand" href="/"></a></div>
                <div class="col-lg-2 p-0">
                    <div class="header-phones">
                    <div><?= Html::tel('+38 (063) 489-26-95',array('class'=>'h6'));?></div>
                    <div><a href="tel:+38 (063) 489-26-95" class="h6">+38 (063) 489-26-95</a></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex align-items-center">
                        <?php $this->widget('mod.shop.blocks.search.SearchWidget', array(/*'skin' => 'current_theme.views.layouts.inc._search'*/)); ?>
                    </div>
                </div>
                <div class="col-lg-3">

                    <?php $this->widget('mod.cart.widgets.cart.CartWidget', array()); ?>


                </div>
            </div>


        </div>


        <nav class="navbar navbar-expand-lg">
            <div class="container megamenu">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                        aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="collapse navbar-collapse " id="navbar">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#">Disabled</a>
                        </li>
                        <li class="nav-item dropdown megamenu-down">
                            <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown07"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                            <div class="dropdown-menu" aria-labelledby="dropdown07">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="dropdown-header">Dropdown header</h6>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6 class="dropdown-header">Dropdown header</h6>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6 class="dropdown-header">Dropdown header</h6>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>


                    </ul>
                    <form class="form-inline my-2 my-md-0">
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                    </form>
                </div>
            </div>
        </nav>
    </header>


<?= CMS::t('UsersModule.default', 'BTN_REGISTER'); ?>
<?= CMS::t('UsersModule.default', 'BTN_LOGIN'); ?>


<?= CMS::t('test', 'TEST2'); ?>