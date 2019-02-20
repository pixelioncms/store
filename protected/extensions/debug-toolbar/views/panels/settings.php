<div data-ydtb-panel-data="<?php echo $this->id ?>">
    <div>
        <div data-ydtb-accordion="<?php echo $this->id?>">
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($application)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Application Properties')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($application as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Modules')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($modules as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($params)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Application Params')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table data-ydtb-data-table="fixed">
                        <tbody>
                            <?php foreach ($params as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($components)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Components')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($components as $key => $value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
