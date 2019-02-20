<?php
$type = Yii::app()->request->getPost('type');
?>

<div class="row">
    <div class="col-lg-12">
        <?php
        if (!$database->checkLimit()) {
            echo Yii::app()->tpl->alert('danger', Yii::t('app', 'BACKUP_LIMIT', array(
                        '{maxsize}' => CMS::files_size($database->limitBackup),
                        '{current_size}' => CMS::files_size($database->checkFilesSize())
                    )), false);
        }
        ?>
    </div>

    <div class="col-lg-6">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => $this->pageName,
        ));
        echo $model->getForm();
        Yii::app()->tpl->closeWidget();

        Yii::app()->tpl->openWidget(array(
            'title' => Yii::t('app', 'DB_LIST')
        ));
        $this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
            'dataProvider' => $data_db,
            'selectableRows' => false,
            'enableHeader' => false,
            'autoColumns' => false,
            'enablePagination' => true,
            'columns' => array(
                array(
                    'name' => 'filename',
                    'header' => 'Название файла',
                    'type' => 'raw',
                    //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
                    'htmlOptions' => array('class' => 'text-left'),
                ),
                array(
                    'name' => 'filesize',
                    'header' => Yii::t('app', 'SIZE'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center'),
                ),
                array(
                    'name' => 'url',
                    'header' => Yii::t('app', 'OPTIONS'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center'),
                ),
            )
                )
        );
        Yii::app()->tpl->closeWidget();
        ?>
    </div>
    <div class="col-lg-6">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => Yii::t('app', 'DB_OPTIMIZE_REPAIR'),
        ));

        $db = Yii::app()->db;
        $dbSchema = $db->schema;
        $tables = array();
        foreach ($dbSchema->getTables() as $tbl) {
            $tbl_name = str_replace('`', '', $tbl->rawName);
            $tables[$tbl_name] = $tbl_name;
        }
        echo Html::form('', 'POST', array('class' => ''));
        ?>
        <div class="form-group" style="display: none;">
            <div class="col-sm-12  text-center">
                <?= Html::dropDownList('datatable[]', null, $tables, array('class' => 'selectpicker', 'multiple' => true)); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                <?php
                echo Html::dropDownList('type',$type,array('optimize' => Yii::t('app', 'OPTIMIZE_DB'), 'repair' => Yii::t('app', 'REPAIR_DB')),array('class'=>'form-control'))
                ?>
                <?= Html::submitButton('OK', array('class' => 'btn btn-success')); ?>

            </div>
        </div>
        <?php
        echo Html::endForm();




$i=0;
$totaltotal=0;
        if ($type == 'optimize') {

$totalfree=0;

$content3='';
            $result = Yii::app()->db->createCommand('SHOW TABLE STATUS FROM `'.CMS::tableName().'`')->queryAll();
            foreach ($result as $row) {
                $total = $row['Data_length'] + $row['Index_length'];
                $totaltotal += $total;
                $free = ($row['Data_free']) ? $row['Data_free'] : 0;
                $totalfree += $free;
                $i++;
                $otitle = (!$free) ? '<span class="badge badge-success">Не нуждается</span>' : '<span class="badge badge-danger">Оптимизирована</span>';
                $result = Yii::app()->db->createCommand("OPTIMIZE TABLE " . $row['Name'] . "")->query();
                $content3 .= "<tr><td class=\"text-center\">" . $i . "</td><td>" . str_replace(Yii::app()->db->tablePrefix,'',$row['Name']) . "</td><td>" . CMS::files_size($total) . "</td><td class=\"text-center\">" . $otitle . "</td><td>" . CMS::files_size($free) . "</td></tr>";
            }
            ?>
            <ul class="list-group">
                <li class="list-group-item"><?= Yii::t('app', 'OPTIMIZE_DB') ?>: <span class="badge badge-secondary"><?= CMS::tableName() ?></span></li>
                <li class="list-group-item"><?= Yii::t('app', 'TOTAL_SIZE_DB') ?>: <span class="badge badge-secondary"><?= CMS::files_size($totaltotal) ?></span></li>
                <li class="list-group-item"><?= Yii::t('app', 'TOTAL_OVERHEAD') ?>: <span class="badge badge-secondary"><?= CMS::files_size($totalfree) ?></span></li>
            </ul>

            <table class="table table-striped table-bordered">
                <tr>
                    <th class="text-center">ID</th>
                    <th><?= Yii::t('app', 'TABLE') ?></th>
                    <th><?= Yii::t('app', 'SIZE') ?></th>
                    <th class="text-center"><?= Yii::t('app', 'STATUS') ?></th>
                    <th><?= Yii::t('app', 'OVERHEAD') ?></th>
                </tr>
                <?= $content3; ?>
            </table>
            <?php
        } elseif ($type == 'repair') {
            $content4='';
            $totaltotal = 0;
            $result = Yii::app()->db->createCommand('SHOW TABLE STATUS FROM `' . CMS::tableName().'`')->queryAll();
            foreach ($result as $row) {
                $total = $row['Data_length'] + $row['Index_length'];
                $totaltotal += $total;
                $i++;

                $rresult = Yii::app()->db->createCommand("REPAIR TABLE " . $row['Name'] . "")->query();
                $otitle = (!$rresult) ? '<span class="badge badge-danger">' . Yii::t('app', 'ERROR') . '</span>' : '<span class="badge badge-success">OK</span>';
                $content4 .= "<tr><td class=\"text-center\">" . $i . "</td><td>" . str_replace(Yii::app()->db->tablePrefix,'',$row['Name']) . "</td><td>" . CMS::files_size($total) . "</td><td class=\"text-center\">" . $otitle . "</td></tr>";
            }
            ?>

            <ul class="list-group">
                <li class="list-group-item"><?= Yii::t('app', 'REPAIR_DB') ?>: <span class="label label-default"><?= CMS::tableName() ?></span></li>
                <li class="list-group-item"><?= Yii::t('app', 'TOTAL_SIZE_DB') ?>: <span class="label label-default"><?= CMS::files_size($totaltotal) ?></span></li>
            </ul>
            <table class="table table-striped table-bordered">
                <tr>
                    <th class="text-center">ID</th>
                    <th><?= Yii::t('app', 'TABLE') ?></th>
                    <th><?= Yii::t('app', 'SIZE') ?></th>
                    <th class="text-center"><?= Yii::t('app', 'STATUS') ?></th>

                </tr>
                <?= $content4; ?>
            </table>
            <?php
        }

        Yii::app()->tpl->closeWidget();
        ?>
    </div></div>