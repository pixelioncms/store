<?php if ($model) { ?>
    <h3><?= Yii::t('ShopModule.default', 'MANUFACTURERS'); ?></h3>
    <div id="<?= $this->id ?>" class="owl-brands owl-carousel">
        <?php foreach ($model as $data) { ?>
                <?php
                echo Html::link(Html::image($data->getImageUrl('image','100x80'), $data->name, array('class' => '')), $data->getUrl(), array('class' => 'image'));
                ?>
        <?php } ?>
    </div>
<?php } ?>