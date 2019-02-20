<?php if (!empty($data)) { ?>
    <table class="table table-striped" id="attributes-list">
        <?php foreach ($data as $title => $value) { ?>
            <tr>
                <td><?= Html::encode($title) ?>:</td>
                <td><?= Html::encode($value) ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>



