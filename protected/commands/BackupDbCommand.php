<?php

/**
 * Console command backup database.
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @package commands
 * @uses CConsoleCommand
 * @version 1.0
 */
class BackupDbCommand extends CConsoleCommand {

    public function run($args) {
        Yii::log('BackupDbCommand begin', 'info', 'console');
        $db = Yii::app()->db;
        $db->enable_limit = false;
        $db->export();
        Yii::log('BackupDbCommand completed', 'info', 'console');
    }

}
