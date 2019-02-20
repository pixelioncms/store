<div class="">
    <?php if (PHP_VERSION_ID < 50300) { ?>
        <?= Yii::app()->tpl->alert('danger', Yii::t('InstallModule.default', 'INSTALL_PHP_VER', array('{current}' => phpversion())), false); ?>
    <?php } else { ?>
        <?= Yii::app()->tpl->alert('info', Yii::t('InstallModule.default', 'INSTALL_INFO'), false); ?>
        <table class="table">
            <?php foreach ($writeAble as $path) { ?>
                <tr>
                    <td><?php echo $path ?></td>
                    <td class="text-center" width="10%">
                        <?php
                        $result = $model->isWritable($path);
                        if ($result)
                            echo '<i class="icon-check text-success"></i>';
                        else {
                            $model->errors = true;
                            echo '<i class="icon-warning text-danger"></i>';
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="2" class="text-center"><?= Yii::t('InstallModule.default', 'ACCESS_FILE_DIR') ?></th>
            </tr>
            <?php
            foreach ($chmod as $file => $ch) {
                $check = CMS::isChmod(Yii::getPathOfAlias("webroot") . DS . $file, $ch);
                ?>
                <tr>
                    <td>
                        <?php echo $file ?>
                        <?php if (!$check) { ?>
                            <small class="text-danger">
                                <?php echo Yii::t('InstallModule.default', 'CHMOD_ERROR', array('{chmod}' => $ch)); ?>
                            </small>
                        <?php }
                        ?>
                    </td>
                    <td class="text-center" width="10%">
                        <?php
                        $check = CMS::isChmod(Yii::getPathOfAlias("webroot") . DS . $file, $ch);
                        if ($check)
                            echo '<i class="icon-check-circle text-success"></i>';
                        else {
                            $model->errors = true;
                            echo '<i class="icon-warning text-danger"></i>';
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <?php if ($model->errors) { ?>
            <div class="rows">

                <?= Yii::app()->tpl->alert('warning', Yii::t('InstallModule.default', 'CORRECT_ERROR'), false); ?>

            </div>
        <?php } ?>
    <?php } ?>
</div>
