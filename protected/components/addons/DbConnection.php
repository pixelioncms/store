<?php

/**
 * DbConnection class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @author Semenov Andrew <info@andrix.com.ua>
 *
 * @link http://pixelion.com.ua PIXELION CMS
 * @link http://andrix.com.ua Developer
 *
 * @package app
 * @subpackage addons
 * @uses CDbConnection
 */
class DbConnection extends CDbConnection
{


    public $backupPath = 'application.backups';
    public $noExportTables = array('surf');
    private $_result = '';
    public $limitBackup;
    public $enable_limit = true;
    public $filesizes = 0;
    public $charset = 'utf8';

    public function __construct($dsn = '', $username = '', $password = '')
    {

        //$this->schemaCachingDuration = YII_DEBUG ? 0 : 3600;
        //$this->emulatePrepare = true;
        //$this->enableProfiling = YII_DEBUG;
        //$this->enableParamLogging = YII_DEBUG;
        parent::__construct($dsn, $username, $password);
    }


    public function checkFilesSize()
    {
        $fdir = opendir(Yii::getPathOfAlias($this->backupPath));
        while ($file = readdir($fdir)) {
            if ($file != '.' & $file != '..' & $file != '.htaccess' & $file != '.gitignore' & $file != 'index.html') {
                $this->filesizes += filesize(Yii::getPathOfAlias($this->backupPath) . DS . $file);
            }
        }
        closedir($fdir);
        return $this->filesizes;
    }

    public function checkLimit()
    {
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

    public function export($withData = true, $dropTable = true, $savePath = true)
    {

        if ($this->checkLimit()) {


            $statments = $this->getPdoInstance()->query("show tables");

            foreach ($statments as $value) {
                $tableName = $value[0];
                if ($dropTable === true) {
                    $tableName2 = str_replace($this->tablePrefix, '{prefix}', $tableName);
                    $this->_result .= "DROP TABLE IF EXISTS `$tableName2`;\n";
                }

                if (!in_array($tableName, $this->noExportTables)) {
                    $tableQuery = $this->getPdoInstance()->query("show create table `$tableName`");
                    $createSql = $tableQuery->fetch();
                    $createSql['Create Table'] = str_replace($this->tablePrefix, '{prefix}', $createSql['Create Table']);
                    $this->_result .= $createSql['Create Table'] . ";\r\n\r\n";
                    if ($withData)
                        $this->withData($tableName);
                }
            }
            $date = date('Y-m-d_Hms');
            $this->_result .= "/*----------------------- BACKUP PIXELION CMS ------------------------------*/\n\r";
            $this->_result .= "/*E-mail: dev@pixelion.com.ua*/\n";
            $this->_result .= "/*Website: www.pixelion.com.ua*/\n";
            $this->_result .= "/*Date backup: " . $date . "*/\n\r";
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
                    '{settings}' => Html::link(Yii::t('app', 'SETTINGS'), array('/admin/security'))
                )));
            }
        } else {
            return 'limited';
        }
    }


    private function withData($tableName)
    {
        $itemsQuery = $this->getPdoInstance()->query("select * from `$tableName`");
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
            $values .= "\n" . $valueString;
        }
        if ($values != "") {
            $tableName = str_replace($this->tablePrefix, '{prefix}', $tableName);
            $tableName = str_replace($this->charset, '{charset}', $tableName);
            $insertSql = "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";\n\r";
            $this->_result .= $insertSql;
        }
    }

    /**
     * import sql from a *.sql file
     *
     * @param string $filePath : with the path and the file name
     * @param string $tables_engine Tables engine, default of MyISAM
     * @return mixed
     * @throws CException|PDOException
     */
    public function importSqlFile($filePath, $tables_engine = 'MyISAM')
    {
        if (file_exists($filePath)) {
            try {
                $sqlStream = file_get_contents($filePath);
                $sqlStream = rtrim($sqlStream);
                $newStream = preg_replace_callback("/\((.*)\)/", function ($matches) {
                    return str_replace(";", " $$$ ", $matches[0]);
                }, $sqlStream);

                $sqlArray = explode(";", $newStream);
                foreach ($sqlArray as $value) {
                    if (!empty($value)) {
                        $value = str_replace("{prefix}", $this->tablePrefix, $value);
                        $value = str_replace("{charset}", $this->charset, $value);
                        $value = str_replace("{engine}", $tables_engine, $value);
                        $sql = str_replace(" $$$ ", ";", $value) . ";";
                        $this->getPdoInstance()->query($sql);
                    }
                }
                return true;

            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
            }
        } else {
            throw new CException("SQL file not found {$filePath}");
        }
    }

}
