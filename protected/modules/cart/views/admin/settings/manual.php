
<div class="container-fluid">
    <div class="row2">
        <?php
        Yii::app()->tpl->alert('info', 'Данный список переменных действует также и на <b>"Все заголовки писем"</b>', false);
        ?>
    </div>
    <?php foreach ($this->module->tpl_keys as $code) { ?>
        <div class="form-group row">
            <div class="col-sm-4 col-md-2"><code><?= Html::clipboard($code); ?></code></div>
            <div class="col-sm-8 col-md-10"><?= Yii::t('CartModule.manual', $code) ?></div>
        </div>
<?php } ?>
</div>
