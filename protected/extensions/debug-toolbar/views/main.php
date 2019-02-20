<div id="ydtb-toolbar" data-ydtb-toolbar class="ydtb-collapse">
    <a href="javascript:;//" data-ydtb-toggle="[data-ydtb-toolbar]" class="debug-toggle"><i class="icon-bug"></i></a>
    <div data-ydtb-sidebar class="bg-dark">
        <h1>CMS <small><?php echo Yii::app()->getVersion() ?> / <?php echo Yii::getVersion() ?></small></h1>
        <?php $this->widget('YiiDebugToolbarResourceUsage'); ?>
        <ul data-ydtb-menu>
            <?php foreach ($panels as $panel): ?>
                <li><a href="javascript:;//" data-ydtb-expand-panel="<?php echo $panel->id ?>"> 
                        <i class="<?php echo $panel->i ?>"></i>
                        <?php if (null !== $panel->menuSubTitle): ?> <small><?php echo $panel->menuSubTitle; ?></small> <?php endif; ?> 
                        <span><?php echo CHtml::encode($panel->menuTitle); ?></span>
                        <span class="clear clearfix"></span>
                    </a></li>
            <?php endforeach; ?>
        </ul>
        <div class="debug-options">
            <i class="icon-arrow-right"></i>
            <i class="icon-arrow-left"></i>
        </div>
    </div>

    <?php foreach ($panels as $panel) : ?>
        <div data-ydtb-panel="<?php echo $panel->id ?>" class="ydtb-collapse">
            <div data-ydtb-panel-header="<?php echo $panel->id ?>">
                <i class="<?php echo $panel->i ?>"></i>
                <?php echo CHtml::encode($panel->title); ?>
                <?php if ($panel->subTitle) : ?>
                    <small><?php echo Html::encode($panel->subTitle); ?> </small>
                <?php endif; ?>
                <ul>
                    <li><i class="icon-lock" data-ydtb-icon="h" data-ydtb-collapse="[data-ydtb-panel]"></i></li>
                    <li><i class="icon-delete" data-ydtb-icon="z" data-ydtb-panel-lock="<?php echo $panel->id ?>"></i></li>
                </ul>
            </div>
            <div data-ydtb-panel-content="<?php echo $panel->id ?>">
                <?php $panel->run(); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
