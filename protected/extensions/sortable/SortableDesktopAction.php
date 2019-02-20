<?php

class SortableDesktopAction extends CAction {

    public $column = 'ordern';
    public $model = null;

    public function run() {
        $ids =  Yii::app()->request->getPost('ids');
        $newColumn = Yii::app()->request->getPost('column_new');
        $desktop_id = (int) Yii::app()->request->getPost('desktop_id');
        if ($ids && is_array($ids)) {


            if ($this->model === null)
                throw new CException('Не указана таблица');

            $sql ="SELECT MAX({$this->column}) FROM " . $this->model->tableName() . " WHERE desktop_id='".$desktop_id."' AND id IN(" . implode(',', $ids) . ")";
            $max = (int) Yii::app()->db->createCommand($sql)->queryScalar();

            if (!is_numeric($max) || $max == 0)
                $this->model->prepareTable($newColumn);
            
          
            $this->model->savePositions($ids, $max);
        }
        //Yii::app()->user->setState(Yii::app()->request->getPost('name'), (array) Yii::app()->request->getPost('clipboard'));
    }

}
