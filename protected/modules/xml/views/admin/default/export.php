
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'priceExportForm',
    'htmlOptions' => array('class' => '')
        ));
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th></th>
            <th><?= Yii::t('app', 'NAME') ?></th>
            <th><?= Yii::t('app', 'ID') ?></th>
        </tr>
    </thead>
    <?php
    foreach ($importer->getImportableAttributes('eav_') as $k => $v) {

        $disabled = in_array($k, array('type', 'name', 'category')) ? 'disabled="disabled"' : '';
        echo '<tr>';
        echo '<td align="left" width="10px"><input type="checkbox" checked name="attributes[]" ' . $disabled . ' value="' . $k . '"></td>';
        echo '<td align="left">' . Html::encode($v) . '</td>';
        echo '<td align="left">' . $k . '</td>';
        echo '</tr>';
    }
    ?>
</table>

<div class="form-group">
    <div class="text-center">
        <input type="submit" value="<?php echo Yii::t('XmlModule.admin', 'DOWNLOAD') ?>" class="btn btn-success">
    </div>
</div>

<?php $this->endWidget(); ?>



