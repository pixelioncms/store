<?php

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * EditableColumn represents a grid view column that is editable.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList.columns
 * @uses CGridColumn
 */
class EditableColumn extends CGridColumn
{

    public $editable = array();

    /**
     * @var string the attribute name of the data model. The corresponding attribute value will be rendered
     * in each data cell. If {@link value} is specified, this property will be ignored
     * unless the column needs to be sortable.
     * @see value
     * @see sortable
     */
    public $name;
    public $type = 'raw';

    /**
     * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
     * as the content of the data cells. In this expression, the variable
     * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
     * and <code>$this</code> the column object.
     */
    public $value;
    public $sortable = true;
    public $filter;
    public $pk;
    // public $inputValue;
    private $jsOptions=array();
    public $uid;

    /**
     * Initializes the column.
     */
    public function init()
    {

        $this->registerScript();
        parent::init();


        if ($this->name === null && $this->value === null)
            throw new CException(Yii::t('zii', 'Either "name" or "value" must be specified for EditableColumn.'));
    }

    /**
     * Renders the filter cell content.
     * This method will render the {@link filter} as is if it is a string.
     * If {@link filter} is an array, it is assumed to be a list of options, and a dropdown selector will be rendered.
     * Otherwise if {@link filter} is not false, a text field is rendered.
     * @since 1.1.1
     */
    public function getFilterCellContent()
    {
        if (is_string($this->filter))
            echo $this->filter;
        elseif ($this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false) {
            if (is_array($this->filter))
                echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id' => false, 'prompt' => '', 'class' => 'form-control'));
            elseif ($this->filter === null)
                echo CHtml::activeTextField($this->grid->filter, $this->name, array('id' => false, 'class' => 'form-control'));
        } else
            parent::getFilterCellContent();
    }

    /**
     * Renders the header cell content.
     * This method will render a link that can trigger the sorting if the column is sortable.
     */
    public function getHeaderCellContent()
    {
        //    $this->htmlOptions = array('class' => 'editable', 'data-id' => $this->pk);
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null)
            echo $this->grid->dataProvider->getSort()->link($this->name, $this->header, array('class' => 'sort-link'));
        elseif ($this->name !== null && $this->header === null) {
            if ($this->grid->dataProvider instanceof ActiveDataProvider)
                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
            else
                echo CHtml::encode($this->name);
        } else
            parent::getHeaderCellContent();
    }

    public function renderDataCell($row)
    {
        $data = $this->grid->dataProvider->data[$row];
        $uid = md5($this->name . $this->grid->dataProvider->data[$row]->primaryKey);


        // $orgValue = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));


        $options = array(
            'class' => 'editable ',
            'id' => 'editable' . $uid,
            'data-toggle' => "tooltip",
            'title' => 'Кликните, для редактирование',
            'data-id' => $this->grid->dataProvider->data[$row]->primaryKey,
            /* 'data-json' => array(
                 'type' => $this->editable['type'],
                 'modelAlias' => $this->editable['modelAlias'] . '.' . $this->grid->dataProvider->modelClass,
                 'attributeName' => $this->name,
                 //'grid' => $this->grid->id,
                 'title' => $data->getAttributeLabel($this->name),
             )*/
        );


        // $options['data-json']=CJSON::encode($options['data-json']);
        if (isset($this->htmlOptions['class'])) {
            $options['class'] .= $this->htmlOptions['class'];
        }
        if ($this->cssClassExpression !== null) {
            $class = $this->evaluateExpression($this->cssClassExpression, array('row' => $row, 'data' => $data));
            if (!empty($class)) {
                if (isset($options['class']))
                    $options['class'] .= ' ' . $class;
                else
                    $options['class'] = $class;
            }
        }
        echo CHtml::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo '</td>';
    }

    /**
     * Renders the data cell content.
     * This method evaluates {@link value} or {@link name} and renders the result.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row, $data)
    {


        $jsOptions = array();
        $uid = md5($this->name . $data->primaryKey);
        $jsOptions['attributeName'] = $this->name;
        $jsOptions['title'] = $data->getAttributeLabel($this->name);
        $jsOptions['grid'] = $this->grid->id;
        $jsOptions['type'] = $this->editable['type'];
        $jsOptions['modelName'] = $this->grid->dataProvider->modelClass;
        if (isset($this->editable['modelAlias'])) {
            $jsOptions['modelAlias'] = $this->editable['modelAlias'].'.' . $this->grid->dataProvider->modelClass;
        }else{
            echo 'Error Editable column $modelAlias';
        }

        if (isset($this->editable['url'])) {
            $jsOptions['url'] = $this->evaluateExpression($this->editable['url'], array('data' => $data, 'row' => $row));

        }

        if (isset($this->editable['items'])) {

            //WARNING! Do not pass associative arrays if the order is important to you.
            //It seems that while FireFox does keep the same order, both Chrome and IE sort it. Here's a little workaround:
            $arWrapper = array();
            $arWrapper['k'] = array_keys($this->editable['items']);
            $arWrapper['v'] = array_values($this->editable['items']);
            $jsOptions['items'] = $arWrapper;

        }
        // $name = $this->name;
        if (isset($this->editable['value'])) {
            $jsOptions['value'] = $this->editable['value'];
            // $this->editable['value3']=$this->grid->dataProvider->data[$row]->{$name};
            $this->editable['value2'] = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        }

        $optionsScript = CJavaScript::encode($jsOptions);

       // $this->jsOptions = CJavaScript::encode($jsOptions);
        $cs = Yii::app()->getClientScript();
        //$cs->registerScriptFile($this->grid->baseScriptUrl . '/editable.js');
        $cs->registerScript('editable' . $uid, "$('#editable{$uid}').editable({$optionsScript});", CClientScript::POS_END);


        if ($this->value !== null) {
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
            $editableValue = '<div class="editable-value">' . $this->evaluateExpression((isset($this->editable['value'])) ? $this->editable['value'] : $this->value, array('data' => $data, 'row' => $row)) . '</div>';
        } elseif ($this->name !== null) {
            $value = CHtml::value($data, $this->name);
        }


        echo $value === null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format("<div class=\"editable-vision\">{$value}</div>" . $editableValue, $this->type);


    }

    protected function registerScript()
    {
        // $this->editable['attributeName'] = $this->name;
        //  $this->editable['title'] = $this->name;
        // $this->editable['grid'] = $this->grid->id;
        //  $this->editable['modelAlias'] .= '.' . $this->grid->dataProvider->modelClass;
        //  $options = CJavaScript::encode($this->editable);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($this->grid->baseScriptUrl . '/editable.js', CClientScript::POS_END);

        // $cs->registerScript('editable'.md5($this->name), "$('.editable').editable({$options});");
        //  $cs->registerScript('editable', "$('.editable').editable();");
    }

}
