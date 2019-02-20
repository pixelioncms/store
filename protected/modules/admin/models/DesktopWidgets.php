<?php

class DesktopWidgets extends ActiveRecord
{

    const MODULE_ID = 'admin';

    public function getForm()
    {
        $ex = array();
        $widgets = Desktop::model()->findByPk($_GET['id']);
        if ($widgets) {
            foreach ($widgets->widgets as $wgt) {
                $ex[] = $wgt->widget_id;
            }
        }
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'dekstop_id' => array('type' => 'hidden', 'value' => $_GET['id']),
                'widget_id' => array(
                    'type' => 'dropdownlist',
                    'items' => Yii::app()->widgets->getData($ex),
                    'empty' => Yii::t('admin', 'Выберите виджет'),
                ),
                'column' => array(
                    'type' => 'dropdownlist',
                    'items' => $this->getColumnsRange(),
                ),
            ),
        ), $this);
    }

    public function tableName()
    {
        return '{{desktop_widgets}}';
    }

    public function relations()
    {
        return array(
            'desktop' => array(self::HAS_ONE, 'Desktop', 'desktop_id'),
        );
    }

    public function behaviors()
    {
        return array('sortable' => 'ext.sortable.SortableDesktopBehavior');
    }

    public function getColumnsRange()
    {
        $desktop = Desktop::model()->findByPk((int)$_GET['id']);
        if (isset($desktop)) {
            $columns = array();
            foreach (range(1, $desktop->columns) as $col) {
                $columns[$col] = $col;
            }
            return $columns;
        } else {
            return false;
        }
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('widget_id, desktop_id, column', 'required'),
            // array('widget_id', 'unique'),
            // array('id', 'UniqueAttributesValidator', 'with' => 'widget_id', 'message' => Yii::t('admin', 'ALREADY_REQUEST_USER')),
            array('desktop_id, column', 'numerical', 'integerOnly' => true),
            array('widget_id', 'length', 'max' => 255),
        );
    }

    public function beforeSave()
    {

        if (parent::beforeSave()) {
            if (isset($_POST['column_new'])) {
                Yii::log('test', 'info', 'application');
                $this->column = (int)Yii::app()->request->getPost('column_new');
                // $this->save(false,false,false);
            }
        }
        return parent::beforeSave();
    }

}
