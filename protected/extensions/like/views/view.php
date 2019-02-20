
<?php if (!Yii::app()->request->isAjaxRequest) { ?>
    <div class="btn-group widget-like <?= $status ?>" id="widget-like-<?= $this->id ?>">
    <?php } ?>
    <a class="btn btn-primary btn-sm like-up" href="javascript:void(0);"><i class="fa fa-thumbs-up"></i></a>
    <span class="btn-group-addon btn-primary like-counter"><?= $counter ?></span>
    <a class="btn btn-primary btn-sm like-down" href="javascript:void(0);"><i class="fa fa-thumbs-down"></i></a>
    <?php if (!Yii::app()->request->isAjaxRequest) { ?>
    </div>
<?php } ?>