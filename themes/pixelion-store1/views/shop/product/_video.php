
<?php if (isset($model->video)) { ?>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= CMS::parse_yturl($model->video) ?>"></iframe>
    </div>
<?php }else{ ?>
<?php Yii::app()->tpl->alert('info','No video ;(',false); ?>
<?php } ?>