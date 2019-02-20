<div class="form-group">
    <div class="col-sm-4">
        <?= Html::activeLabel($model, 'video'); ?>
    </div>
    <div class="col-sm-8">
        <?= Html::activeTextField($model, 'video', array('class' => 'form-control')); ?>
        <?= Html::error($model, 'video'); ?>
    </div>
</div>
<?php if (!empty($model->video)) { ?>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= CMS::parse_yturl($model->video) ?>"></iframe>
    </div>
<?php }else{ ?>
<?php Yii::app()->tpl->alert('info','No video ;(',false); ?>
<?php } ?>
