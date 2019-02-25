<?php if ($model->type->attribute_group) { ?>
    <div class="row">
        <?php foreach ($groups as $group_name => $group) { ?>
            <div class="col-md-12 col-lg-6">
                <table class="table table-sm table-attributes">
                    <thead>
                    <tr>
                        <th colspan="2"><h5><?= Html::encode($group_name) ?></h5></th>

                    </tr>
                    </thead>
                    <?php foreach ($group as $item) { ?>
                        <tr>
                            <td>

                                <span>
                                    <?= Html::encode($item['name']) ?>
                                    <?php if (!empty($item['hint'])) { ?>
                                        <a href="javascript:void(0)" title="<?= Html::encode($item['name']) ?>"
                                           class="attribute-popover<?= $item['id']; ?>"><i class="icon-info"></i></a>

                                <?php Yii::app()->clientScript->registerScript('attribute-popover-' . $item['id'], "$('.attribute-popover" . $item['id'] . "').popover({html: true,trigger: 'focus',content: function () {return $('#attribute-popover-" . $item['id'] . "').html();}});"); ?>

                                        <div id="attribute-popover-<?= $item['id']; ?>" class="d-none">
                                        <?= $item['hint']; ?>
                                    </div>
                                    <?php } ?>
                                </span>


                            </td>
                            <td class="text-right"><span
                                        class="font-weight-bold"><?= Html::encode($item['value']) ?></span></td>
                        </tr>

                    <?php } ?>
                </table>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <?php if (!empty($data)) { ?>
        <table class="table table-striped" id="attributes-list">
            <?php foreach ($data as $title => $value) { ?>
                <?php if (!empty($value)) { ?>
                    <tr>
                        <td><?= Html::encode($title) ?>:</td>
                        <td><span class="font-weight-bold"><?= Html::encode($value) ?></span></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>
    <?php } ?>
<?php } ?>
