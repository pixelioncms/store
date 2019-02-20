<?php

/**
 * @author Troy <troytft@gmail.com>
 */
class SortableBehavior extends CActiveRecordBehavior
{

    /**
     * @var string  Field to store sorting
     */
    public $column = 'ordern';

    public function beforeFind($event)
    {
        $criteria = $this->owner->getDbCriteria();
        $alias = $this->owner->getTableAlias(true);

        if (!$criteria->order)
            $criteria->order = $alias . ".`{$this->column}` DESC";
        //    parent::beforeFind($event);
    }

    public function beforeSave($event)
    {

        $model = $this->owner;
        $column = $this->column;
        if ($model->isNewRecord)
            $model->$column = Yii::app()->db->createCommand("SELECT MAX({$this->column}) FROM " . $model->tableName())->queryScalar() + 1;
        // parent::beforeSave($event);
    }

    public function savePositions($ids, $start)
    {
        $priorities = array();
        foreach ($ids as $id)
            $priorities[$id] = $start--;

        //if ($this->owner instanceof AttachmentModel && Yii::app()->request->getQuery('param')) {
        //    Yii::log($this->owner->object_id,'info','application');
        //    Yii::log($this->owner->model,'info','application');
        //    Yii::app()->db->createCommand("UPDATE " . $this->owner->tableName() . " SET {$this->column} = " . $this->_generateCase($priorities) . " WHERE model='" . Yii::app()->request->getQuery('param') . "' AND id IN(" . implode(', ', $ids) . ")")->execute();
        //} else {
            Yii::app()->db->createCommand("UPDATE " . $this->owner->tableName() . " SET {$this->column} = " . $this->_generateCase($priorities) . " WHERE id IN(" . implode(', ', $ids) . ")")->execute();
       // }
    }

    /**
     * Prepare table
     */
    public function prepareTable()
    {
        Yii::app()->db->createCommand("UPDATE " . $this->owner->tableName() . " SET {$this->column} = id")->execute();
    }

    private function _generateCase($priorities)
    {
        $result = 'CASE `id`';
        foreach ($priorities as $k => $v)
            $result .= ' when "' . $k . '" then "' . $v . '"';
        return $result . ' END';
    }

}
