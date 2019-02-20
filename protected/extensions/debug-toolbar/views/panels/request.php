<div data-ydtb-panel-data="<?php echo $this->id ?>">
    <div>
        <div data-ydtb-accordion="<?php echo $this->id?>">
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($server)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Request Server Parameters')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table data-ydtb-data-table="fixed">
                        <tbody>
                            <?php $c=0; foreach ($server as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($cookies)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Request Cookies')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($cookies as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($session)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Session Attributes')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($session as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($get)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Request GET Parameters')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($get as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($post)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Request POST Parameters')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($post as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
            <div data-ydtb-accordion-group="collapsed">
                <div data-ydtb-accordion-heading="" data-ydtb-data-size="<?php echo count($files)?>">
                    <i class="icon-menu"></i>
                    <span><?php echo YiiDebug::t('Request FILES')?></span>
                    <i class="icon-arrow-down"></i>
                    <i class="icon-arrow-up"></i>
                    <div class="clear clearfix"></div>
                </div>
                <div data-ydtb-accordion-body="">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($files as $key=>$value) : ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $this->dump($value); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

