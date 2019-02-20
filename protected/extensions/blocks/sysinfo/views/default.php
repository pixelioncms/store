<table class="table table-striped">
    <tr>
        <td width="50%">CMS version / Yii: <span class="float-right"><?= $cms_ver; ?> <?= $yii_ver; ?></span></td>
        <td width="50%">PDO extension: <span class="float-right"><?= $pdo ?></td>
    </tr>
    <tr>
        <td>PHP version <span class="float-right"><?= $php ?></span></td>
        <td>MySQL: <span class="float-right"><?= Yii::app()->db->getServerVersion() ?></span></td>
    </tr>
    <tr>
        <td>Upload_max_filesize <span class="float-right"><?= $u_max ?></span></td>
        <td>Register_globals <span class="float-right"><?= $globals ?></span></td>
    </tr>
    <tr>
        <td>Memory_limit <span class="float-right"><?= $m_max ?></span></td>
        <td>Magic_quotes_gpc <span class="float-right"><?= $magic_quotes ?></span></td>
    </tr>
    <tr>
        <td>Post_max_size <span class="float-right"><?= $p_max ?></span></td>
        <td>Libery GD <span class="float-right"><?= $gd ?></span></td>
    </tr>
    <tr>
        <td>System TimeZone <span class="float-right"><?= $timezone ?></span></td>
        <td>OS: <span class="float-right"><?= $os ?></span></td>
    </tr>
    <tr>
        <td>Backup dir size <span class="float-right"><?= $backup_dir_size ?></span></td>
        <td>Uplaods dir size <span class="float-right"><?= $uploads_dir_size ?></span></td>
    </tr>
    <tr>
        <td>Assets dir size <span class="float-right"><?= $assets_dir_size ?></span></td>
        <td>Cache dir size <span class="float-right"><?= $cache_dir_size ?></span></td>
    </tr>

</table>


<?= Html::form('', 'post', array('class' => 'form-horizontal')) ?>
<div class="form-group row">
    <label class="col-sm-4 col-form-label">Кэш:</label>
    <div class="col-sm-8">
        <div class="input-group">
            <?php
            echo Html::dropDownList('cache_id', '', array(
                'cached_settings' => 'settings',
                'cached_widgets' => 'cached_widgets',
                'url_manager_urls' => 'url_manager_urls'
            ), array(
                'class' => 'custom-select',
                'empty' => Yii::t('app', 'EMPTY_LIST')
            ))
            ?>
            <div class="input-group-append">
                <input class="btn btn-success float-right" type="submit" value="<?= Yii::t('app', 'CLEAR'); ?>">
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 col-form-label">Активы (/assets):</label>
    <div class="col-sm-8">
        <?= Html::hiddenField('clear_assets', 1, array('class' => 'form-control')); ?>
        <input class="btn btn-success float-right" style="margin-left:10px;" type="submit"
               value="<?= Yii::t('app', 'CLEAR'); ?>">
    </div>
</div>
<?= Html::endForm(); ?>
<?php


