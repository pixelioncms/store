<tr data-image="<?= $data->getMainImageUrl('270x347') ?>" class="grid-table-row">
    <td>
        <div class="photo"><?= Html::image($data->getMainImageUrl('270x347'), $data->name, array('data-echo' => $data->getMainImageUrl('270x347'))) ?></div>
        <div class="btn-group-vertical">
            <a href="#" class="btn btn-default btn-xs view_table_image">
                <i class="fa fa-image"></i>
                <img src="<?=$data->getMainImageUrl('270x347')?>" alt="" />
            </a>
            <?php
            if (Yii::app()->hasModule('compare')) {
                echo Html::link('<i class="fa fa-balance-scale"></i>', 'javascript:compare.add(' . $data->id . ');', array(
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'В сравнение',
                    'class' => 'btn btn-default btn-xs'
                ));
            }
            if (Yii::app()->hasModule('wishlist')) {
                echo Html::link('<i class="fa fa-heart"></i>', 'javascript:wishlist.add(' . $data->id . ');', array(
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'В избранное',
                    'class' => 'btn btn-default btn-xs'
                ));
            }
            ?>


        </div>
    </td>
    <td>
        <?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?>
        <?php if (!empty($data->sku)) { ?>
            <div class="hint small"><?= $data->getAttributeLabel('sku') ?>: <?= $data->sku ?></div>
        <?php } ?>
        <div class="" style="margin-top: 5px;">
            <span class="label label-warning">Хит</span>
            <?php
            if (Yii::app()->hasModule('discounts')) {
                if ($data->appliedDiscount) {
                    ?>
                    <span class="label label-success">скидка <?= $data->discountSum ?></span>
                    <?php
                }
            }
            ?> 
        </div>
    </td>
    <td>
    </span>
<?php $this->widget('ext.rating.StarRating', array('model' => $data)); ?>
</td>
<td>

    <span class="price">
        <span><?php echo $data->priceRange() ?></span>
        <small><?= Yii::app()->currency->active->symbol ?></small>
    </span>

    <?php
    if (Yii::app()->hasModule('discounts')) {
        if ($data->appliedDiscount) {
            ?>
            <div>
                <span class="price price-xs price-through">
                    <span><?= $data->toCurrentCurrency('originalPrice') ?></span>
                    <small><?= Yii::app()->currency->active->symbol ?></small>
                </span>
            </div>
            <?php
        }
    }
    ?>   


</td>
<td class="text-right">
    <?php
    echo $data->beginCartForm();
    ?>
    <div class="product-action">
        <div class="btn-group btn-group-sm">
            <?php
            if ($data->isAvailable) {
                echo Html::link(Yii::t('app', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-success'));
            } else {
                echo Html::link(Yii::t('app', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
            }
            ?>
        </div>
    </div>
    <?php echo $data->endCartForm(); ?>
</td>
</tr>


