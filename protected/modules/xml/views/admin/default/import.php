<script>
    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready(function () {
        $('.btn-file :file').on('fileselect', function (event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
            } else {
                if (log)
                    alert(log);
            }
        });
    });
</script>

<?php
if (!Yii::app()->request->isAjaxRequest) {
    Yii::app()->tpl->openWidget(array(
        'title' => $this->pageName,
    ));
}
?>



<div class="row">
    <div class="col-lg-12">
        <?php if ($importer->hasErrors()) { ?>
            <div class="alert alert-danger"><p>Ошибки импорта:</p>
                <ul>
                    <?php
                    $i = 0;
                    foreach ($importer->getErrors() as $error) {
                        if ($i < 10) {
                            if ($error['line'] > 0)
                                echo "<li>" . Yii::t('admin', 'Строка') . ": " . $error['line'] . ". " . $error['error'] . "</li>";
                            else
                                echo "<li>" . $error['error'] . "</li>";
                        }
                        else {
                            $n = count($importer->getErrors()) - $i;
                            echo '<li>' . Yii::t('admin', 'и еще({n}).', array('{n}' => $n)) . '</li>';
                            break;
                        }
                        $i++;
                    }
                    ?>
                </ul>
            </div>
        <?php } ?>

        <?php if ($importer->stats['create'] > 0 OR $importer->stats['update'] > 0) { ?>
            <div class="alert alert-success">
                <?php echo Yii::t('XmlModule.admin', 'CREATE_PRODUCTS', array('{n}' => $importer->stats['create'])); ?>
                <br/>
                <?php echo Yii::t('XmlModule.admin', 'UPDATE_PRODUCTS', array('{n}' => $importer->stats['update'])); ?>
            </div>
        <?php } ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fileUploadForm',
            'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => '')
        ));
        ?>







        <div class="form-group">
            <div class="col-sm-12">
                <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            <?= Yii::t('XmlModule.admin', 'SELECT_FILE') ?> <input type="file" name="file">
                        </span>
                    </span>
                    <input type="text" class="form-control" readonly>
                    <span class="input-group-btn">
                        <input type="submit" value="<?= Yii::t('XmlModule.admin', 'START_IMPORT') ?>" class="btn btn-success">
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12"><label style="width: 300px"><input type="checkbox" name="create_dump" value="1" checked="checked" /> <?= Yii::t('XmlModule.admin', 'DUMP_DB') ?></label></div>
        </div>

        <div class="form-group">
            <div class="col-sm-12"><label style="width: 300px"><input type="checkbox" name="remove_images" value="1" checked="checked" /> <?= Yii::t('XmlModule.admin', 'REMOVE_IMAGES') ?></label></div>
        </div>







        <div class="form-group">
            <div class="btn-group">
                <a class="btn btn-info" href="<?php echo $this->createUrl('sample') ?>"><?= Yii::t('XmlModule.admin', 'DOWNLOAD') ?></a>
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#xmlguide" aria-expanded="false" aria-controls="collapseExample">
                    Документация
                </a>
            </div>       
        </div>
        <?php $this->endWidget(); ?>
        <div class="collapse" id="xmlguide">
            <?php $this->renderPartial('_documentation', array()); ?>
        </div>

    </div>
</div>




<?php
if (!Yii::app()->request->isAjaxRequest)
    Yii::app()->tpl->closeWidget();
?>




