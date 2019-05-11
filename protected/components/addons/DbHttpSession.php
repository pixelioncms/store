<?php

class DbHttpSession extends CDbHttpSession
{
    public $sessionTableName = '{{session}}';


    /**
     * Updates the current session id with a newly generated one.
     * Please refer to {@link http://php.net/session_regenerate_id} for more details.
     * @param boolean $deleteOldSession Whether to delete the old associated session file or not.
     * @since 1.1.8
     */
    public function regenerateID22($deleteOldSession = false)
    {
        $oldID = session_id();

        // if no session is started, there is nothing to regenerate
        if (empty($oldID))
            return;

        parent::regenerateID(false);
        $newID = session_id();
        $db = $this->getDbConnection();

        $row = $db->createCommand()
            ->select()
            ->from($this->sessionTableName)
            ->where('id=:id', array(':id' => $oldID))
            ->queryRow();
        if ($row !== false) {
            if ($deleteOldSession)
                $db->createCommand()->update($this->sessionTableName, array(
                    'id' => $newID
                ), 'id=:oldID', array(':oldID' => $oldID));
            else {
                $row['id'] = $newID;
                //$row['current_url'] = CMS::gen(10);
                $db->createCommand()->insert($this->sessionTableName, $row);
            }
        } else {
            // shouldn't reach here normally
            $db->createCommand()->insert($this->sessionTableName, array(
                'id' => $newID,
                'expire' => time() + $this->getTimeout(),
                'data' => '',
            ));
        }
    }

    /**
     * Session write handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @param string $data session data
     * @return boolean whether session write is successful
     */
    public function writeSession($id, $data)
    {


        // exception must be caught in session write handler
        // http://us.php.net/manual/en/function.session-set-save-handler.php
        try {

            $url = htmlspecialchars(getenv("REQUEST_URI"));
            $ip = Yii::app()->request->userHostAddress;


            //if (Yii::app()->hasComponent('user')) {
            if (Yii::app()->user->isGuest) {
                $checkBot = CMS::isBot();
                if ($checkBot['success']) {
                    $uname = substr($checkBot['name'], 0, 25);
                    $user_type = 'SearchBot';
                } else {
                    $uname = $ip;
                    $user_type = 'Guest';
                }
            } else {
                $uname = Yii::app()->user->username;
                if (Yii::app()->user->isSuperuser) {
                    $user_type = 'Admin';
                } else {
                    if (Yii::app()->user) {
                        $user_type = implode(',', Yii::app()->user->getRoles());
                    } else {
                        $user_type = 'user';
                    }
                }
            }
            //}


            $expire = time() + $this->getTimeout();
            $db = $this->getDbConnection();
            if ($db->getDriverName() == 'pgsql')
                $data = new CDbExpression($db->quoteValueWithType($data, PDO::PARAM_LOB) . "::bytea");
            if ($db->getDriverName() == 'sqlsrv' || $db->getDriverName() == 'mssql' || $db->getDriverName() == 'dblib')
                $data = new CDbExpression('CONVERT(VARBINARY(MAX), ' . $db->quoteValue($data) . ')');
            if ($db->createCommand()->select('id')->from($this->sessionTableName)->where('id=:id', array(':id' => $id))->queryScalar() === false) {
                $db->createCommand()->insert($this->sessionTableName, array(
                    'id' => $id,
                    'data' => $data,
                    'start_expire' => time(),
                    'expire' => $expire,
                    'current_url' => $url,
                    'ip_address' => $ip,
                    'user_name' => $uname,
                    'user_type' => $user_type,
                    'user_agent' => Yii::app()->request->userAgent,
                ));
            } else {

                //todo: Panix isAjaxRequest no job with module cart.
                // if (!Yii::app()->request->isAjaxRequest) {
                $db->createCommand()->update($this->sessionTableName, array(
                    'data' => $data,
                    'expire' => $expire,
                    'user_id' => (!Yii::app()->user->isGuest) ? Yii::app()->user->id : NULL,
                    'current_url' => $url,
                    'user_type' => $user_type,
                    'user_name' => $uname,
                ), 'id=:id', array(':id' => $id));
                // }
            }
        } catch (Exception $e) {
            if (YII_DEBUG)
                echo $e->getMessage();
            // it is too late to log an error message here
            return false;
        }
        return true;
    }

    /**
     * Creates the session DB table.
     * @param DbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    protected function createSessionTable($db, $tableName)
    {
        switch ($db->getDriverName()) {
            case 'mysql':
                $blob = 'LONGBLOB';
                break;
            case 'pgsql':
                $blob = 'BYTEA';
                break;
            case 'sqlsrv':
            case 'mssql':
            case 'dblib':
                $blob = 'VARBINARY(MAX)';
                break;
            default:
                $blob = 'BLOB';
                break;
        }
        $db->createCommand()->createTable($tableName, array(
            'id' => 'CHAR(32) PRIMARY KEY',
            'user_id' => 'int(4) unsigned DEFAULT NULL',
            'start_expire' => 'integer',
            'expire' => 'integer',
            'data' => $blob,
            'ip_address' => 'varchar(60)',
            'current_url' => 'varchar(255)',
            'user_type' => 'varchar(50)',
            'user_name' => 'varchar(60)',
            'user_agent' => 'text',
        ));
    }
}
