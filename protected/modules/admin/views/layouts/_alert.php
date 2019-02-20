<div class="alert alert-<?= $type ?> <?= $htmlClass ?>" id="alert<?= md5($type . CMS::translit($message)) ?>">
    <?= $message ?>

    <? if ($close) { ?>
        <button type="button" class="close" onClick="common.close_alert('<?= md5($type . CMS::translit($message)) ?>'); return false;" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <? } ?>
</div>
