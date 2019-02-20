<?php

class SortableDesktopBehavior extends CActiveRecordBehavior {

    /**
     * @var string  Field to store sorting
     */
    public $column = 'ordern';

    public function beforeFind($event) {
        $criteria = $this->owner->getDbCriteria();
        $alias = $this->owner->getTableAlias(true);

        if (!$criteria->order)
            $criteria->order = $alias . ".{$this->column} DESC";
        //    parent::beforeFind($event);
    }

    public function beforeSave($event) {

        $model = $this->owner;
        $column = $this->column;
        if ($model->isNewRecord) {
          //todo: desktop_id
            $model->$column = Yii::app()->db->createCommand("SELECT MAX({$this->column}) FROM " . $model->tableName())->queryScalar() + 1;
        }
        parent::beforeSave($event);
    }

    public function savePositions($ids, $start) {
        $priorities = array();
        foreach ($ids as $id)
            $priorities[$id] = $start--;

        $alias = $this->owner->getTableAlias(true);
        $newColumn = (int) Yii::app()->request->getPost('column_new');
        $desktop_id = (int) Yii::app()->request->getPost('desktop_id');

        //find last column widget
        //die("SELECT " . $this->owner->tableName() . " WHERE id='{$desktop_id}'");
        //$last = Yii::app()->db->createCommand("SELECT * FROM " . $this->owner->tableName() . " WHERE id='{$desktop_id}'")->queryRow();

        //if($last){
        //    print_r($last);
        //    die;
        //}


///column='{$newColumn}',
        $sql="UPDATE " . $this->owner->tableName() . " SET {$this->column} = " . $this->_generateCase($priorities) . " WHERE id IN(" . implode(',', $ids) . ")";
//die($sql);
        Yii::app()->db->createCommand($sql)->execute();

    }

    /**
     * Prepare table
     */
    public function prepareTable($newColumn) {

        Yii::app()->db->createCommand("UPDATE " . $this->owner->tableName() . " SET column='{$newColumn}', {$this->column} = id")->execute();
    }

    private function _generateCase($priorities) {
        $result = 'CASE `id`';
        foreach ($priorities as $k => $v)
            $result .= ' WHEN "' . $k . '" THEN "' . $v . '"';
        return $result . ' END';
    }

}
