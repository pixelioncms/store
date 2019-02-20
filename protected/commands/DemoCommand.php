<?php

class DemoCommand extends CConsoleCommand
{

    public function run($args)
    {
        $dbFilePath = Yii::getPathOfAlias("app") . DS . 'dump_store.sql';
        $zipFilePath = Yii::getPathOfAlias('app') . DS . 'dump_store.zip';
        $db = Yii::app()->db;
        $tables = $db->schema->getTableNames();
        foreach ($tables as $table) {
            echo 'Delete table: ' . $table . PHP_EOL;
            $db->createCommand()->dropTable($table);
        }
        echo 'All tables deleted.' . PHP_EOL;
        echo 'Start import SQL file' . PHP_EOL;
Yii::log('DemoCommand','info','console;');
        if ($db->importSqlFile($dbFilePath)) {
            echo 'Start remove dir manufacturer.' . PHP_EOL;
            array_map('unlink', glob(Yii::getPathOfAlias('webroot.uploads.manufacturer') . "/*"));
            echo 'Start remove dir attachments.product.' . PHP_EOL;
            array_map('unlink', glob(Yii::getPathOfAlias('webroot.uploads.attachments.product') . "/*"));
            $zip = Yii::app()->zip;
            echo 'Start extractZip.' . PHP_EOL;
            $zip->extractZip($zipFilePath, Yii::getPathOfAlias("webroot.uploads"));
            Yii::app()->cache->flush();
        }
    }


}
