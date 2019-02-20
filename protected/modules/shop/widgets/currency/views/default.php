<ul id="currencies">
    <li class="dropdown">
        <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= Yii::app()->currency->active->iso ?>
            <span class="caret"></span>
        </a>
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
</ul>