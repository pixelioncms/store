<?php if (!empty($this->model->products)) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class="heading-title"><?= $this->pageName; ?> <a class="btn btn-primary" href="mailto:?body=<?= $this->model->getUrl() ?>&subject=<?= Yii::t('WishlistModule.default', 'SUBJECT_NAME') ?>"><?= Yii::t('app', 'SEND') ?></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($this->model->products as $p) {
                            $this->renderPartial('_product', array(
                                'data' => $p,
                            ));
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } else { ?>
    <?php Yii::app()->tpl->alert('info', Yii::t('WishlistModule.default', 'EMPTY_LIST')); ?>
<?php } ?>