<?php

class SortableAction extends CAction
{

    public $column = 'ordern';
    public $model = null;

    public function run()
    {
        if (isset($_POST['ids']) && is_array($_POST['ids'])) {

            if ($this->model === null)
                throw new CException('Не указана таблица');

            if ($this->model instanceof AttachmentModel) {
                $q_model =  Yii::app()->request->getQuery('param');
                $q_objectId =  Yii::app()->request->getQuery('object_id');
                $max = (int)Yii::app()->db->createCommand("SELECT MAX({$this->column}) FROM " . $this->model->tableName() . " WHERE model='" . $q_model . "' AND object_id='" . $q_objectId . "'  AND id IN(" . implode(', ', $_POST['ids']) . ")")->queryScalar();
            } else {
                $max = (int)Yii::app()->db->createCommand("SELECT MAX({$this->column}) FROM " . $this->model->tableName() . " WHERE id IN(" . implode(', ', $_POST['ids']) . ")")->queryScalar();
            }

            if (!is_numeric($max) || $max == 0)
                $this->model->prepareTable();


            $this->model->savePositions($_POST['ids'], $max);
        }
        //Yii::app()->user->setState(Yii::app()->request->getPost('name'), (array) Yii::app()->request->getPost('clipboard'));
    }

}
