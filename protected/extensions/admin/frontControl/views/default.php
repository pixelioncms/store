<div class="btn-group2 control-panel position-<?=$this->options['position']?>">
    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split2" data-toggle="dropdown"><i class="icon-settings"></i> Опции</button>
    <ul class="dropdown-menu dropdown-menu-right float2-<?=$this->options['position']?>">
        <?= $this->renderItems(); ?>
    </ul>
</div>

