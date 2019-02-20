<div class="alert alert-<?= $type ?>" id="alert<?= md5($type . CMS::translit($message)) ?>">
    <?= $message ?>

    <? if ($close) { ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick="common.close_alert('<?= md5($type . CMS::translit($message)) ?>'); return false;">
            <span aria-hidden="true">&times;</span>
        </button>

    <? } ?>
</div>