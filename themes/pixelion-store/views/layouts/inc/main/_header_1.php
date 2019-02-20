<?php
$this->widget('ext.admin.sitePanel.PanelWidget');
Yii::import('mod.compare.CompareModule');
Yii::import('mod.wishlist.WishlistModule');
Yii::import('mod.wishlist.models.Wishlist');
?>



<?php if (Yii::app()->settings->get('seo', 'googletag_id')) { ?>
    <noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?= Yii::app()->settings->get('seo', 'googletag_id') ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php } ?>
<header class="header-style-1">
    <div class="top-bar animate-dropdown">
        <div class="container">
            <div class="header-top-inner">

                <div class="cnt-account">
                    <ul class="list-unstyled com-heart">
                        <?php //$this->widget('mod.users.widgets.login.LoginWidget', array());  ?>
                        <li><?= Html::link(Yii::t('CompareModule.default', 'COMPARE', array('{c}' => CompareProducts::countSession())), array('/compare'), array('class' => 'top-compare')) ?></li>
                        <li><?= Html::link(Yii::t('WishlistModule.default', 'WISHLIST', array('{c}' => Wishlist::model()->countBy())), array('/wishlist'), array('class' => 'top-wishlist')) ?></li>


                    </ul>
                    <?php $this->widget('mod.shop.widgets.currency.CurrencyWidget', array()); ?>
                    <?php $this->widget('ext.blocks.chooseLanguage.ChooseLanguage', array()); ?>
                    <?php $this->widget('mod.users.widgets.login.LoginWidget', array()); ?>
                </div><!-- /.cnt-account -->

                <div class="cnt-block">

                    <ul class="list-unstyled list-inline">
                        <li class="dropdown dropdown-small">
                            <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><span class="value">English </span><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">English</a></li>
                                <li><a href="#">French</a></li>
                                <li><a href="#">German</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- ============================================== TOP MENU : END ============================================== -->
    <div class="main-header">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 logo-holder">
                    <div class="logo">
                        <a href="/">
                            <img src="//corner-cms.com/logo.php?size=200x60&color=085b9a&type=logo_cms" alt="">
                        </a>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-7 col-md-6 top-search-holder">
                    <?php $this->widget('mod.shop.blocks.search.SearchWidget', array('skin' => 'current_theme.views.layouts.inc._search')); ?>
                </div>

                <div class="col-xs-12 col-sm-5 col-md-3 animate-dropdown top-cart-row">
                    <?php $this->widget('mod.cart.widgets.cart.CartWidget', array('skin' => 'current_theme.views.layouts.inc._cart_opened')); ?>
                </div>
            </div>

        </div>

    </div>

    <!-- ============================================== NAVBAR ============================================== -->
    <div class="header-nav animate-dropdown">
        <div class="container">
            <div class="yamm navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button data-target="#mc-horizontal-menu-collapse" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="nav-bg-class">
                    <div class="navbar-collapse collapse" id="mc-horizontal-menu-collapse">
                        <div class="nav-outer">
                            <ul class="nav navbar-nav">
                                <li class="active dropdown yamm-fw">
                                    <?= Html::link('<i class="icon-home"></i>', '/'); ?>
                                </li>
                                <li><?= Html::link('О нас', array('/page/o_nas')); ?></li>
                                <li><?= Html::link('Доставка и оплата <span class="menu-label new-menu hidden-xs">new</span>', array('/page/o_nas')); ?></li>
                                <li><?= Html::link('Доставка и оплата', array('/page/o_nas')); ?></li>
                                <li class="dropdown yamm mega-menu">
                                    <a href="home.html" data-hover="dropdown" class="dropdown-toggle" data-toggle="dropdown">Clothing</a>
                                    <ul class="dropdown-menu container">
                                        <li>
                                            <div class="yamm-content ">
                                                <div class="row">

                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-menu">
                                                        <h2 class="title">Men</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Dresses</a></li>
                                                            <li><a href="#">Shoes </a></li>
                                                            <li><a href="#">Jackets</a></li>
                                                            <li><a href="#">Sunglasses</a></li>
                                                            <li><a href="#">Sport Wear</a></li>
                                                            <li><a href="#">Blazers</a></li>
                                                            <li><a href="#">Shirts</a></li>

                                                        </ul>
                                                    </div><!-- /.col -->

                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-menu">
                                                        <h2 class="title">Women</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Handbags</a></li>
                                                            <li><a href="#">Jwellery</a></li>
                                                            <li><a href="#">Swimwear </a></li>                   
                                                            <li><a href="#">Tops</a></li>
                                                            <li><a href="#">Flats</a></li>
                                                            <li><a href="#">Shoes</a></li>
                                                            <li><a href="#">Winter Wear</a></li>

                                                        </ul>
                                                    </div><!-- /.col -->

                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-menu">
                                                        <h2 class="title">Boys</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Toys & Games</a></li>
                                                            <li><a href="#">Jeans</a></li>
                                                            <li><a href="#">Shirts</a></li>
                                                            <li><a href="#">Shoes</a></li>
                                                            <li><a href="#">School Bags</a></li>
                                                            <li><a href="#">Lunch Box</a></li> 
                                                            <li><a href="#">Footwear</a></li>

                                                        </ul>
                                                    </div><!-- /.col -->

                                                    <div class="col-xs-12 col-sm-6 col-md-2 col-menu">
                                                        <h2 class="title">Girls</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Sandals </a></li> 
                                                            <li><a href="#">Shorts</a></li>
                                                            <li><a href="#">Dresses</a></li>
                                                            <li><a href="#">Jwellery</a></li>
                                                            <li><a href="#">Bags</a></li>
                                                            <li><a href="#">Night Dress</a></li>
                                                            <li><a href="#">Swim Wear</a></li>


                                                        </ul>
                                                    </div><!-- /.col -->


                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-menu banner-image">
                                                        <img class="img-responsive" src="<?= $this->assetsUrl ?>/images/top-menu-banner.jpg" alt="">






                                                    </div><!-- /.yamm-content -->					
                                                </div>
                                            </div>

                                        </li>
                                    </ul>

                                </li>

                                <li class="dropdown mega-menu">
                                    <a href="category.html"  data-hover="dropdown" class="dropdown-toggle" data-toggle="dropdown">Electronics
                                        <span class="menu-label hot-menu hidden-xs">hot</span>
                                    </a>
                                    <ul class="dropdown-menu container">
                                        <li>
                                            <div class="yamm-content">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-2 col-menu">
                                                        <h2 class="title">Laptops</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Gaming</a></li>
                                                            <li><a href="#">Laptop Skins</a></li>
                                                            <li><a href="#">Apple</a></li>
                                                            <li><a href="#">Dell</a></li>
                                                            <li><a href="#">Lenovo</a></li>
                                                            <li><a href="#">Microsoft</a></li>
                                                            <li><a href="#">Asus</a></li>
                                                            <li><a href="#">Adapters</a></li>
                                                            <li><a href="#">Batteries</a></li>
                                                            <li><a href="#">Cooling Pads</a></li>
                                                        </ul>
                                                    </div><!-- /.col -->

                                                    <div class="col-xs-12 col-sm-12 col-md-2 col-menu">
                                                        <h2 class="title">Desktops</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Routers & Modems</a></li>
                                                            <li><a href="#">CPUs, Processors</a></li>
                                                            <li><a href="#">PC Gaming Store</a></li>
                                                            <li><a href="#">Graphics Cards</a></li>
                                                            <li><a href="#">Components</a></li>
                                                            <li><a href="#">Webcam</a></li>
                                                            <li><a href="#">Memory (RAM)</a></li>
                                                            <li><a href="#">Motherboards</a></li>
                                                            <li><a href="#">Keyboards</a></li>
                                                            <li><a href="#">Headphones</a></li>
                                                        </ul>
                                                    </div><!-- /.col -->

                                                    <div class="col-xs-12 col-sm-12 col-md-2 col-menu">
                                                        <h2 class="title">Cameras</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Accessories</a></li>
                                                            <li><a href="#">Binoculars</a></li>
                                                            <li><a href="#">Telescopes</a></li>
                                                            <li><a href="#">Camcorders</a></li>
                                                            <li><a href="#">Digital</a></li>
                                                            <li><a href="#">Film Cameras</a></li>
                                                            <li><a href="#">Flashes</a></li>
                                                            <li><a href="#">Lenses</a></li>
                                                            <li><a href="#">Surveillance</a></li>
                                                            <li><a href="#">Tripods</a></li>

                                                        </ul>
                                                    </div><!-- /.col -->
                                                    <div class="col-xs-12 col-sm-12 col-md-2 col-menu">
                                                        <h2 class="title">Mobile Phones</h2>
                                                        <ul class="links">
                                                            <li><a href="#">Apple</a></li>
                                                            <li><a href="#">Samsung</a></li>
                                                            <li><a href="#">Lenovo</a></li>
                                                            <li><a href="#">Motorola</a></li>
                                                            <li><a href="#">LeEco</a></li>
                                                            <li><a href="#">Asus</a></li>
                                                            <li><a href="#">Acer</a></li>
                                                            <li><a href="#">Accessories</a></li>
                                                            <li><a href="#">Headphones</a></li>
                                                            <li><a href="#">Memory Cards</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-menu custom-banner">
                                                        <a href="#"><img alt="" src="<?= $this->assetsUrl ?>/images/banner-side.png"></a>
                                                    </div>
                                                </div><!-- /.row -->
                                            </div><!-- /.yamm-content -->					</li>
                                    </ul>
                                </li>




                                <li>
                                    <?= Html::link('Контакты', array('/contacts')); ?>
                                </li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown">Pages</a>
                                    <ul class="dropdown-menu pages">
                                        <li>
                                            <div class="yamm-content">
                                                <div class="row">

                                                    <div class="col-xs-12 col-menu">
                                                        <ul class="links">
                                                            <li><a href="home.html">Home</a></li>
                                                            <li><a href="category.html">Category</a></li>
                                                            <li><a href="detail.html">Detail</a></li>
                                                            <li><a href="shopping-cart.html">Shopping Cart Summary</a></li>
                                                            <li><a href="checkout.html">Checkout</a></li>
                                                            <li><a href="blog.html">Blog</a></li>
                                                            <li><a href="blog-details.html">Blog Detail</a></li>
                                                            <li><a href="contact.html">Contact</a></li>
                                                            <li><a href="sign-in.html">Sign In</a></li>
                                                            <li><a href="my-wishlist.html">Wishlist</a></li>
                                                            <li><a href="terms-conditions.html">Terms and Condition</a></li>
                                                            <li><a href="track-orders.html">Track Orders</a></li>
                                                            <li><a href="product-comparison.html">Product-Comparison</a></li>
                                                            <li><a href="faq.html">FAQ</a></li>
                                                            <li><a href="404.html">404</a></li>

                                                        </ul>
                                                    </div>



                                                </div>
                                            </div>
                                        </li>



                                    </ul>
                                </li>
                                <li class="dropdown  navbar-right special-menu">
                                    <a href="#">Todays offer</a>
                                </li>


                            </ul><!-- /.navbar-nav -->
                            <div class="clearfix"></div>				
                        </div><!-- /.nav-outer -->
                    </div><!-- /.navbar-collapse -->


                </div><!-- /.nav-bg-class -->
            </div><!-- /.navbar-default -->
        </div><!-- /.container-class -->

    </div><!-- /.header-nav -->
    <!-- ============================================== NAVBAR : END ============================================== -->

</header>