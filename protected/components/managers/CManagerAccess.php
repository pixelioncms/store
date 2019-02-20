<?php

/**
 * CManagerAccess class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @uses CComponent
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class CManagerAccess extends CComponent {

    protected $user_id;
    protected $_rules = array();

    public function init() {
        $this->_rules = Yii::app()->db->createCommand()
                ->select('name, description')
                ->from('{{authitem}}')
                ->where('type=2')
                ->queryAll();


        $this->user_id = (!Yii::app()->user->isGuest) ? Yii::app()->user->id : false;
    }

    public function getName($accessLevel) {
        if (is_numeric($accessLevel)) {
            $accessData = $this->dataList();
            return $accessData[$accessLevel];
        } else {
            throw new Exception('$accessLevel должен быть числом');
        }
    }

    /**
     * with module rights
     * 
     * @param int $accessLevel Default 0 all members access
     * @return boolean
     */
    public function check($accessLevel = 0) {
        $accessArray = $this->dataList();
        if (isset($accessArray[$accessLevel])) {
            if ($accessLevel == 0) {
                return true;
            } elseif (Yii::app()->user->checkAccess($accessArray[$accessLevel])) {
                return true;
            }
        }
        return false;
    }

    public function dataList() {
        $array = array();
        foreach (CMap::mergeArray(array(array('name' => Yii::t('app','ALL_USERS'))), $this->_rules) as $key => $value) {
            $array[$key] = $value['name'];
        }
        return $array;
    }

    public function getUserRole() {
        $array = array();
        foreach (Rights::getAssignedRoles($this->user_id) as $role) {
            $array[] = $role->name;
        }
        return $array;
    }

    public function checkUserRole($user_id) {
        foreach (Rights::getAssignedRoles($user_id) as $role) {
            echo $role->name . '<br/>';
        }
    }

}
