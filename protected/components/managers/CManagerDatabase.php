<?php

/**
 * CManagerDatabase
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @uses CComponent
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class CManagerDatabase extends CComponent {

    public $backupPath = 'application.backups';
    private $prefix;
    private $charset = 'utf8';
    public $noExportTables = array('surf');
    private $_pdo;
    private $_result = '';
    public $limitBackup;
    public $enable_limit = true;
    public $filesizes = 0;

    //const limit = 5; //50 мб

    public function init() {
        if (Yii::app()->hasComponent('settings')) {
            $this->limitBackup = (int) Yii::app()->settings->get('database', 'backup_limit') * 1024 * 1024;
        }
        $this->prefix = Yii::app()->db->tablePrefix;
        $this->charset = Yii::app()->db->charset;
        $this->_pdo = Yii::app()->db->pdoInstance;
        $result = array();
        foreach ($this->noExportTables as $table) {
            $result[] = $this->prefix . $table;
        }
        $this->noExportTables = $result;
    }

    public function checkFilesSize() {
        $fdir = opendir(Yii::getPathOfAlias($this->backupPath));
        while ($file = readdir($fdir)) {
            if ($file != '.' & $file != '..' & $file != '.htaccess' & $file != '.gitignore' & $file != 'index.html') {
                $this->filesizes += filesize(Yii::getPathOfAlias($this->backupPath) . DS . $file);
            }
        }
        closedir($fdir);
        return $this->filesizes;
    }

    public function checkLimit() {
        if ($this->enable_limit) {
            if ($this->checkFilesSize() <= $this->limitBackup) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function export($withData = true, $dropTable = true, $savePath = true) {

        if ($this->checkLimit()) {

            $this->prefix = Yii::app()->db->tablePrefix;

            $statments = $this->_pdo->query("show tables");

            foreach ($statments as $value) {
                $tableName = $value[0];
                if ($dropTable === true) {
                    $tableName2 = str_replace($this->prefix, '{prefix}', $tableName);
                    $this->_result.="DROP TABLE IF EXISTS `$tableName2`;\n";
                }

                if (!in_array($tableName, $this->noExportTables)) {
                    $tableQuery = $this->_pdo->query("show create table `$tableName`");
                    $createSql = $tableQuery->fetch();
                    $createSql['Create Table'] = str_replace($this->prefix, '{prefix}', $createSql['Create Table']);
                    $this->_result.=$createSql['Create Table'] . ";\r\n\r\n";
                    if ($withData)
                        $this->withData($tableName);
                }
            }
            $date = date('Y-m-d_Hms');
            $this->_result.="/*----------------------- BACKUP PIXELION CMS ------------------------------*/\n\r";
            $this->_result.="/*E-mail: info@pixelion.com.ua*/\n";
            $this->_result.="/*Website: www.pixelion.com.ua*/\n";
            $this->_result.="/*Date backup: " . $date . "*/\n\r";
            ob_start();
            echo $this->_result;
            $content = ob_get_contents();
            ob_end_clean();
            $content = gzencode($content, 9);
            $saveName = $date . ".sql.gz";
            if ($savePath === false) {
                $request = Yii::app()->getRequest();
                $request->sendFile($saveName, $content);
            } else {
                file_put_contents(Yii::getPathOfAlias($this->backupPath) . DS . $saveName, $content);
                return true;
            }

            if (Yii::app()->controller instanceof AdminController) {
                Yii::app()->controller->setFlashMessage(Yii::t('app', 'BACKUP_DB_SUCCESS', array(
                            '{settings}' => Html::link(Yii::t('app', 'SETTINGS'), array('/admin/core/security'))
                )));
            }
        }
    }

    private function withData($tableName) {
        $itemsQuery = $this->_pdo->query("select * from `$tableName`");
        $values = "";
        $items = "";
        while ($itemQuery = $itemsQuery->fetch(PDO::FETCH_ASSOC)) {
            $itemNames = array_keys($itemQuery);
            $itemNames = array_map("addslashes", $itemNames);
            $items = join('`,`', $itemNames);
            $itemValues = array_values($itemQuery);
            $itemValues = array_map("addslashes", $itemValues);
            $valueString = join("','", $itemValues);
            $valueString = "('" . $valueString . "'),";
            $values.="\n" . $valueString;
        }
        if ($values != "") {
            $tableName = str_replace($this->prefix, '{prefix}', $tableName);
            $tableName = str_replace($this->charset, '{charset}', $tableName);
            $insertSql = "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";\n\r";
            $this->_result.=$insertSql;
        }
    }

    /**
     * import sql from a *.sql file
     *
     * @param string $file: with the path and the file name
     * @return mixed
     */
    public function import($mod, $fileName = 'dump.sql') {
        $file = Yii::getPathOfAlias("mod.{$mod}.sql") . DS . $fileName;
        if (file_exists($file)) {
            //$this->_pdo = Yii::app()->db->pdoInstance;
            try {
                if (file_exists($file)) {
                    $sqlStream = file_get_contents($file);
                    $sqlStream = rtrim($sqlStream);
                    $newStream = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $sqlStream);
                    $sqlArray = explode(";", $newStream);
                    foreach ($sqlArray as $value) {
                        if (!empty($value)) {
                            $value = str_replace("{prefix}", $this->prefix, $value);
                            $value = str_replace("{charset}", $this->charset, $value);
                            $sql = str_replace(" $$$ ", ";", $value) . ";";
                            $this->_pdo->exec($sql);
                        }
                    }
                    Yii::log('Success import db ' . $mod, 'info', 'install');
                    return true;
                }
            } catch (PDOException $e) {
                Yii::log('Error install DB', 'info', 'install');
                echo $e->getMessage();
                exit;
            }
        } else {
            throw new CException("no find {$fileName}");
        }
    }


}
