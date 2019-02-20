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


    private $_attributes = array();
    private $_active = false;
    private $_pdo;
    private $_transaction;
    private $_schema;

    /**
     * Constructor.
     * Note, the DB connection is not established when this connection
     * instance is created. Set {@link setActive active} property to true
     * to establish the connection.
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     * @param string $username The user name for the DSN string.
     * @param string $password The password for the DSN string.
     * @see http://www.php.net/manual/en/function.PDO-construct.php
     */
    public function __construct($dsn = '', $username = '', $password = '')
    {

        $this->schemaCachingDuration = YII_DEBUG ? 0 : 3600;
        $this->charset = 'utf8';
        $this->emulatePrepare = true;
        $this->enableProfiling = YII_DEBUG;
        $this->enableParamLogging = YII_DEBUG;
        parent::__construct($dsn, $username, $password);
    }


    /**
     * Initializes the component.
     * This method is required by {@link IApplicationComponent} and is invoked by application
     * when the CDbConnection is used as an application component.
     * If you override this method, make sure to call the parent implementation
     * so that the component can be marked as initialized.
     */
    public function init()
    {
        parent::init();
        if ($this->autoConnect)
            $this->setActive(true);
    }

    /**
     * Returns whether the DB connection is established.
     * @return boolean whether the DB connection is established
     */
    public function getActive()
    {
        return $this->_active;
    }

    /**
     * Open or close the DB connection.
     * @param boolean $value whether to open or close DB connection
     * @throws CException if connection fails
     */
    public function setActive($value)
    {
        if ($value != $this->_active) {
            if ($value)
                $this->open();
            else
                $this->close();
        }
    }


    /**
     * Opens DB connection if it is currently not
     * @throws CException if connection fails
     */
    protected function open()
    {
        if ($this->_pdo === null) {
            if (empty($this->connectionString))
                throw new CDbException('CDbConnection.connectionString cannot be empty.');
            try {
                Yii::trace('Opening DB connection', 'system.db.CDbConnection');
                $this->_pdo = $this->createPdoInstance();
                $this->initConnection($this->_pdo);
                $this->_active = true;
            } catch (PDOException $e) {
                Yii::app()->request->redirect('/install.php?error=db_connection');
            }
        }
    }


    /**
     * Creates the PDO instance.
     * When some functionalities are missing in the pdo driver, we may use
     * an adapter class to provide them.
     * @throws CDbException when failed to open DB connection
     * @return PDO the pdo instance
     */
    protected function createPdoInstance()
    {
        $pdoClass = $this->pdoClass;
        if (($pos = strpos($this->connectionString, ':')) !== false) {
            $driver = strtolower(substr($this->connectionString, 0, $pos));
            if ($driver === 'mssql' || $driver === 'dblib')
                $pdoClass = 'CMssqlPdoAdapter';
            elseif ($driver === 'sqlsrv')
                $pdoClass = 'CMssqlSqlsrvPdoAdapter';
        }

        if (!class_exists($pdoClass))
            throw new CDbException(Yii::t('yii', 'CDbConnection is unable to find PDO class "{className}". Make sure PDO is installed correctly.', array('{className}' => $pdoClass)));

        @$instance = new $pdoClass($this->connectionString, $this->username, $this->password, $this->_attributes);

        if (!$instance)
            throw new CDbException(Yii::t('yii', 'CDbConnection failed to open the DB connection.'));

        return $instance;
    }

    /**
     * Initializes the open db connection.
     * This method is invoked right after the db connection is established.
     * The default implementation is to set the charset for MySQL and PostgreSQL database connections.
     * @param PDO $pdo the PDO instance
     */
    protected function initConnection($pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($this->emulatePrepare !== null && constant('PDO::ATTR_EMULATE_PREPARES'))
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, $this->emulatePrepare);
        if ($this->charset !== null) {
            $driver = strtolower($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
            if (in_array($driver, array('pgsql', 'mysql', 'mysqli')))
                $pdo->exec('SET NAMES ' . $pdo->quote($this->charset));
        }
        if ($this->initSQLs !== null) {
            foreach ($this->initSQLs as $sql)
                $pdo->exec($sql);
        }
    }

    /**
     * Returns the PDO instance.
     * @return PDO the PDO instance, null if the connection is not established yet
     */
    public function getPdoInstance()
    {
        return $this->_pdo;
    }

    /**
     * Creates a command for execution.
     * @param mixed $query the DB query to be executed. This can be either a string representing a SQL statement,
     * or an array representing different fragments of a SQL statement. Please refer to {@link CDbCommand::__construct}
     * for more details about how to pass an array as the query. If this parameter is not given,
     * you will have to call query builder methods of {@link CDbCommand} to build the DB query.
     * @return CDbCommand the DB command
     */
    public function createCommand($query = null)
    {
        $this->setActive(true);
        return new CDbCommand($this, $query);
    }

    /**
     * Returns the currently active transaction.
     * @return CDbTransaction the currently active transaction. Null if no active transaction.
     */
    public function getCurrentTransaction()
    {
        if ($this->_transaction !== null) {
            if ($this->_transaction->getActive())
                return $this->_transaction;
        }
        return null;
    }

    /**
     * Starts a transaction.
     * @return CDbTransaction the transaction initiated
     */
    public function beginTransaction()
    {
        Yii::trace('Starting transaction', 'system.db.CDbConnection');
        $this->setActive(true);
        $this->_pdo->beginTransaction();
        return $this->_transaction = new CDbTransaction($this);
    }

    /**
     * Returns the database schema for the current connection
     * @throws CDbException if CDbConnection does not support reading schema for specified database driver
     * @return CDbSchema the database schema for the current connection
     */
    public function getSchema()
    {
        if ($this->_schema !== null)
            return $this->_schema;
        else {
            $driver = $this->getDriverName();
            if (isset($this->driverMap[$driver]))
                return $this->_schema = Yii::createComponent($this->driverMap[$driver], $this);
            else
                throw new CDbException(Yii::t('yii', 'CDbConnection does not support reading schema for {driver} database.', array('{driver}' => $driver)));
        }
    }

    /**
     * Returns the SQL command builder for the current DB connection.
     * @return CDbCommandBuilder the command builder
     */
    public function getCommandBuilder()
    {
        return $this->getSchema()->getCommandBuilder();
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     * @param string $sequenceName name of the sequence object (required by some DBMS)
     * @return string the row ID of the last row inserted, or the last value retrieved from the sequence object
     * @see http://www.php.net/manual/en/function.PDO-lastInsertId.php
     */
    public function getLastInsertID($sequenceName = '')
    {
        $this->setActive(true);
        return $this->_pdo->lastInsertId($sequenceName);
    }

    /**
     * Quotes a string value for use in a query.
     * @param string $str string to be quoted
     * @return string the properly quoted string
     * @see http://www.php.net/manual/en/function.PDO-quote.php
     */
    public function quoteValue($str)
    {
        if (is_int($str) || is_float($str))
            return $str;

        $this->setActive(true);
        if (($value = $this->_pdo->quote($str)) !== false)
            return $value;
        else  // the driver doesn't support quote (e.g. oci)
            return "'" . addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032") . "'";
    }

    /**
     * Quotes a table name for use in a query.
     * If the table name contains schema prefix, the prefix will also be properly quoted.
     * @param string $name table name
     * @return string the properly quoted table name
     */
    public function quoteTableName($name)
    {
        return $this->getSchema()->quoteTableName($name);
    }

    /**
     * Quotes a column name for use in a query.
     * If the column name contains prefix, the prefix will also be properly quoted.
     * @param string $name column name
     * @return string the properly quoted column name
     */
    public function quoteColumnName($name)
    {
        return $this->getSchema()->quoteColumnName($name);
    }

    /**
     * Determines the PDO type for the specified PHP type.
     * @param string $type The PHP type (obtained by gettype() call).
     * @return integer the corresponding PDO type
     */
    public function getPdoType($type)
    {
        static $map = array
        (
            'boolean' => PDO::PARAM_BOOL,
            'integer' => PDO::PARAM_INT,
            'string' => PDO::PARAM_STR,
            'resource' => PDO::PARAM_LOB,
            'NULL' => PDO::PARAM_NULL,
        );
        return isset($map[$type]) ? $map[$type] : PDO::PARAM_STR;
    }

    /**
     * Returns the case of the column names
     * @return mixed the case of the column names
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function getColumnCase()
    {
        return $this->getAttribute(PDO::ATTR_CASE);
    }

    /**
     * Sets the case of the column names.
     * @param mixed $value the case of the column names
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function setColumnCase($value)
    {
        $this->setAttribute(PDO::ATTR_CASE, $value);
    }

    /**
     * Returns how the null and empty strings are converted.
     * @return mixed how the null and empty strings are converted
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function getNullConversion()
    {
        return $this->getAttribute(PDO::ATTR_ORACLE_NULLS);
    }

    /**
     * Sets how the null and empty strings are converted.
     * @param mixed $value how the null and empty strings are converted
     * @see http://www.php.net/manual/en/pdo.setattribute.php
     */
    public function setNullConversion($value)
    {
        $this->setAttribute(PDO::ATTR_ORACLE_NULLS, $value);
    }

    /**
     * Returns whether creating or updating a DB record will be automatically committed.
     * Some DBMS (such as sqlite) may not support this feature.
     * @return boolean whether creating or updating a DB record will be automatically committed.
     */
    public function getAutoCommit()
    {
        return $this->getAttribute(PDO::ATTR_AUTOCOMMIT);
    }

    /**
     * Sets whether creating or updating a DB record will be automatically committed.
     * Some DBMS (such as sqlite) may not support this feature.
     * @param boolean $value whether creating or updating a DB record will be automatically committed.
     */
    public function setAutoCommit($value)
    {
        $this->setAttribute(PDO::ATTR_AUTOCOMMIT, $value);
    }

    /**
     * Returns whether the connection is persistent or not.
     * Some DBMS (such as sqlite) may not support this feature.
     * @return boolean whether the connection is persistent or not
     */
    public function getPersistent()
    {
        return $this->getAttribute(PDO::ATTR_PERSISTENT);
    }

    /**
     * Sets whether the connection is persistent or not.
     * Some DBMS (such as sqlite) may not support this feature.
     * @param boolean $value whether the connection is persistent or not
     */
    public function setPersistent($value)
    {
        return $this->setAttribute(PDO::ATTR_PERSISTENT, $value);
    }

    /**
     * Returns the name of the DB driver
     * @return string name of the DB driver
     */
    public function getDriverName()
    {
        if (($pos = strpos($this->connectionString, ':')) !== false)
            return strtolower(substr($this->connectionString, 0, $pos));
        // return $this->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Returns the version information of the DB driver.
     * @return string the version information of the DB driver
     */
    public function getClientVersion()
    {
        return $this->getAttribute(PDO::ATTR_CLIENT_VERSION);
    }

    /**
     * Returns the status of the connection.
     * Some DBMS (such as sqlite) may not support this feature.
     * @return string the status of the connection
     */
    public function getConnectionStatus()
    {
        return $this->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    /**
     * Returns whether the connection performs data prefetching.
     * @return boolean whether the connection performs data prefetching
     */
    public function getPrefetch()
    {
        return $this->getAttribute(PDO::ATTR_PREFETCH);
    }

    /**
     * Returns the information of DBMS server.
     * @return string the information of DBMS server
     */
    public function getServerInfo()
    {
        return $this->getAttribute(PDO::ATTR_SERVER_INFO);
    }

    /**
     * Returns the version information of DBMS server.
     * @return string the version information of DBMS server
     */
    public function getServerVersion()
    {
        return $this->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    /**
     * Returns the timeout settings for the connection.
     * @return integer timeout settings for the connection
     */
    public function getTimeout()
    {
        return $this->getAttribute(PDO::ATTR_TIMEOUT);
    }

    /**
     * Obtains a specific DB connection attribute information.
     * @param integer $name the attribute to be queried
     * @return mixed the corresponding attribute information
     * @see http://www.php.net/manual/en/function.PDO-getAttribute.php
     */
    public function getAttribute($name)
    {
        $this->setActive(true);
        return $this->_pdo->getAttribute($name);
    }

    /**
     * Sets an attribute on the database connection.
     * @param integer $name the attribute to be set
     * @param mixed $value the attribute value
     * @see http://www.php.net/manual/en/function.PDO-setAttribute.php
     */
    public function setAttribute($name, $value)
    {
        if ($this->_pdo instanceof PDO)
            $this->_pdo->setAttribute($name, $value);
        else
            $this->_attributes[$name] = $value;
    }

    /**
     * Returns the attributes that are previously explicitly set for the DB connection.
     * @return array attributes (name=>value) that are previously explicitly set for the DB connection.
     * @see setAttributes
     * @since 1.1.7
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * Sets a set of attributes on the database connection.
     * @param array $values attributes (name=>value) to be set.
     * @see setAttribute
     * @since 1.1.7
     */
    public function setAttributes($values)
    {
        foreach ($values as $name => $value)
            $this->_attributes[$name] = $value;
    }

    /**
     * Returns the statistical results of SQL executions.
     * The results returned include the number of SQL statements executed and
     * the total time spent.
     * In order to use this method, {@link enableProfiling} has to be set true.
     * @return array the first element indicates the number of SQL statements executed,
     * and the second element the total time spent in SQL execution.
     */
    public function getStats()
    {
        $logger = Yii::getLogger();
        $timings = $logger->getProfilingResults(null, 'system.db.CDbCommand.query');
        $count = count($timings);
        $time = array_sum($timings);
        $timings = $logger->getProfilingResults(null, 'system.db.CDbCommand.execute');
        $count += count($timings);
        $time += array_sum($timings);
        return array($count, $time);
    }

    public $backupPath = 'application.backups';
    public $noExportTables = array('surf');
    private $_result = '';
    public $limitBackup;
    public $enable_limit = true;
    public $filesizes = 0;


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


            $statments = $this->_pdo->query("show tables");

            foreach ($statments as $value) {
                $tableName = $value[0];
                if ($dropTable === true) {
                    $tableName2 = str_replace($this->tablePrefix, '{prefix}', $tableName);
                    $this->_result .= "DROP TABLE IF EXISTS `$tableName2`;\n";
                }

                if (!in_array($tableName, $this->noExportTables)) {
                    $tableQuery = $this->_pdo->query("show create table `$tableName`");
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
     * @throws CException
     */
    public function importSqlFile($filePath, $tables_engine = 'MyISAM')
    {
        if (file_exists($filePath)) {
            //$this->_pdo = Yii::app()->db->pdoInstance;
            try {
                $sqlStream = file_get_contents($filePath);
                $sqlStream = rtrim($sqlStream);
                if (phpversion() < '7.2') {
                    $newStream = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $sqlStream);
                } else {
                    $newStream = preg_replace_callback("/\((.*)\)/", function ($matches) {
                        return str_replace(";", " $$$ ", $matches[0]);
                    }, $sqlStream);
                }

                $sqlArray = explode(";", $newStream);
                foreach ($sqlArray as $value) {
                    if (!empty($value)) {
                        $value = str_replace("{prefix}", $this->tablePrefix, $value);
                        $value = str_replace("{charset}", $this->charset, $value);
                        $value = str_replace("{engine}", $tables_engine, $value);
                        $sql = str_replace(" $$$ ", ";", $value) . ";";
                        $this->_pdo->exec($sql);
                    }
                }
                Yii::log('Success import db ' . $filePath, 'info', 'application');
                return true;

            } catch (PDOException $e) {
                Yii::log('Error import db', 'info', 'application');
                echo $e->getMessage();
                exit;
            }
        } else {
            throw new CException("no find {$filePath}");
        }
    }

}
