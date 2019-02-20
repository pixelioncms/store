<li class="dropdown dropdown-small">
    <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><span class="key"><?php echo Yii::t('ShopModule.default', 'CURRENCY'); ?> :</span><span class="value"><?= Yii::app()->currency->active->iso ?> </span><b class="caret"></b></a>
    <ul class="dropdown-menu">
        <?php
        foreach (Yii::app()->currency->currencies as $currency) {
            echo Html::openTag('li');
            echo Html::ajaxLink($currency->iso, '/shop/ajax/activateCurrency/' . $currency->id, array(
                'success' => 'js:function(){window.location.reload(true)}',
                    ), array('id' => 'sw' . $currency->id, 'class' => Yii::app()->currency->active->id === $currency->id ? 'active' : ''));
            echo Html::closeTag('li');
        }
        ?>
    </ul>
</li>


