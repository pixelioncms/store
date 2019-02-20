<div class="btn-group widget-like <?= $status ?>" id="widget-like-<?= $this->id ?>">
    <a class="btn btn-primary btn-sm like-up" href="javascript:like('up','<?= htmlspecialchars($json)?>');"><i class="fa fa-thumbs-up"></i></a>
    <span class="btn-group-addon btn-primary like-counter"><?= $counter ?></span>
    <a class="btn btn-primary btn-sm like-down" href="javascript:like('down','<?= htmlspecialchars($json)?>');"><i class="fa fa-thumbs-down"></i></a>
</div>