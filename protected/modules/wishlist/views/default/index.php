

<h1><?= $this->pageName; ?></h1>

<?php if (!empty($this->model->products)) { ?>

    <a class="btn btn-primary" href="mailto:?body=<?= $this->model->getUrl() ?>&subject=<?= Yii::t('WishlistModule.default', 'SUBJECT_NAME') ?>"><?= Yii::t('app', 'SEND') ?></a>

    <div class="products_list wish_list">
        <?php
        foreach ($this->model->products as $p) {
            $this->renderPartial('_product', array(
                'data' => $p,
            ));
        }
        ?>
    </div>
<?php } else { ?>
    <?php Yii::app()->tpl->alert('info', Yii::t('WishlistModule.default', 'EMPTY_LIST')); ?>
<?php } ?>