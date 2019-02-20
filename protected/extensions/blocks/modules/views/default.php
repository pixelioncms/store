<ul>
    <?php foreach ($model as $key => $row) { ?>
        <li><a href="<?= $row->getInfo()->url ?>"><?php echo Yii::t(ucfirst($row->name) . 'Module.default', 'MODULE_NAME') ?></a></li>
    <?php } ?>
</ul>
