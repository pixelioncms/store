<?php

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * TelColumn class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList.columns
 * @uses CGridColumn
 */
class TelColumn extends CGridColumn {

    public $name;
    public $value;
    public $type = 'html';

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions = array('class' => 'tel-column');

    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions = array('class' => 'tel-column');

    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions = array('class' => 'tel-column');

    /**
     * @var array the HTML options for the checkboxes.
     */
    public $checkBoxHtmlOptions = array();

    public function getHeaderCellContent() {
        if ($this->grid->enableSorting && $this->name !== null)
            echo $this->grid->dataProvider->getSort()->link($this->name, $this->header, array('class' => 'sort-link'));
        elseif ($this->name !== null && $this->header === null) {
            if ($this->grid->dataProvider instanceof ActiveDataProvider)
                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
            else
                echo CHtml::encode($this->name);
        } else
            parent::getHeaderCellContent();
    }

    protected function renderDataCellContent($row, $data) {
        if ($this->value !== null)
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        elseif ($this->name !== null)
            $value = CHtml::value($data, $this->name);
        echo Html::tel($value);
    }

}
