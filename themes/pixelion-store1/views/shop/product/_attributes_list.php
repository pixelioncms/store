<?php if (Yii::app()->settings->get('shop', 'group_attribute')) { ?>
    <div class="row">
        <?php foreach ($groups as $group_name => $group) { ?>
            <div class="col-sm-6">
                <table class="table table-sm table-attributes">
                    <thead>
                    <tr>
                        <th colspan="2"><?= Html::encode($group_name) ?></th>

                    </tr>
                    </thead>
                    <?php foreach ($group as $item) { ?>
                        <tr>
                            <td>

                                <span><?= Html::encode($item['name']) ?></span>
                                <?php if (!empty($item['hint'])) { ?>
                                    <i title="<?= Html::encode($item['name']) ?>" class="attribute-popover<?= $item['id']; ?> icon-info"></i>
                                    <script>
                                        $(function () {
                                            $('.attribute-popover<?= $item['id']; ?>').popover({
                                                html: true,
                                                trigger: 'click',
                                                content: function () {
                                                    return $("#attribute-popover-<?= $item['id']; ?>").html();
                                                }
                                            });
                                        });
                                    </script>
                                    <div id="attribute-popover-<?= $item['id']; ?>" class="d-none">
                                        <?= $item['hint']; ?>
                                    </div>
                                <?php } ?>

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
                <tr>
                    <td><?= Html::encode($title) ?>:</td>
                    <td><span class="font-weight-bold"><?= Html::encode($value) ?></span></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
<?php } ?>
