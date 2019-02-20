<div data-ydtb-tabs="<?php echo $this->id?>">
    <ul class="ydtb-toolbar-navbarlist">
        <li><a href="#summary"><i class="icon-database"></i><?php echo YiiDebug::t('Summary')?></a></li>
        <li><a href="#callstack"><i class="icon-books"></i><?php echo YiiDebug::t('Callstack')?></a></li>
        <li><a href="#servers"><i class="icon-database"></i><?php echo YiiDebug::t('Servers')?></a></li>
    </ul>
    <div data-ydtb-panel-data="<?php echo $this->id ?>">
            <div>
                <div data-ydtb-tab="servers">
                    <?php $this->render('sql/servers', array(
                        'connections'=>$connections
                    )) ?>
                </div>
                <div data-ydtb-tab="summary">
                    <?php $this->render('sql/summary', array(
                        'summary'=>$summary
                    )) ?>
                </div>
                <div data-ydtb-tab="callstack">
                    <?php $this->render('sql/callstack', array(
                        'callstack'=>$callstack
                    )) ?>
                </div>
            </div>
    </div>
</div>
