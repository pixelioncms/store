<?php

/**
 * CFormInputElement class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @package components
 */

/**
 * CFormInputElement represents form input element.
 *
 * CFormInputElement can represent the following types of form input based on {@link type} property:
 * <ul>
 * <li>text: a normal text input generated using {@link CHtml::activeTextField}</li>
 * <li>hidden: a hidden input generated using {@link CHtml::activeHiddenField}</li>
 * <li>password: a password input generated using {@link CHtml::activePasswordField}</li>
 * <li>textarea: a text area generated using {@link CHtml::activeTextArea}</li>
 * <li>file: a file input generated using {@link CHtml::activeFileField}</li>
 * <li>radio: a radio button generated using {@link CHtml::activeRadioButton}</li>
 * <li>checkbox: a check box generated using {@link CHtml::activeCheckBox}</li>
 * <li>listbox: a list box generated using {@link CHtml::activeListBox}</li>
 * <li>dropdownlist: a drop-down list generated using {@link CHtml::activeDropDownList}</li>
 * <li>checkboxlist: a list of check boxes generated using {@link CHtml::activeCheckBoxList}</li>
 * <li>radiolist: a list of radio buttons generated using {@link CHtml::activeRadioButtonList}</li>
 * <li>url: an HTML5 url input generated using {@link CHtml::activeUrlField}</li>
 * <li>email: an HTML5 email input generated using {@link CHtml::activeEmailField}</li>
 * <li>number: an HTML5 number input generated using {@link CHtml::activeNumberField}</li>
 * <li>range: an HTML5 range input generated using {@link CHtml::activeRangeField}</li>
 * <li>date: an HTML5 date input generated using {@link CHtml::activeDateField}</li>
 * </ul>
 * The {@link type} property can also be a class name or a path alias to the class. In this case,
 * the input is generated using a widget of the specified class. Note, the widget must
 * have a property called "model" which expects a model object, and a property called "attribute"
 * which expects the name of a model attribute.
 *
 * Because CFormElement is an ancestor class of CFormInputElement, a value assigned to a non-existing property will be
 * stored in {@link attributes} which will be passed as HTML attribute values to the {@link CHtml} method
 * generating the input or initial values of the widget properties.
 *
 * @property boolean $required Whether this input is required.
 * @property string $label The label for this input. If the label is not manually set,
 * this method will call {@link CModel::getAttributeLabel} to determine the label.
 *
 * @since 1.1
 * @uses CFormInputElement Yii framework
 */
class FormInputElement extends CFormInputElement {

    /**
     * @var array Core input types (alias=>CHtml method name)
     */
    public static $coreTypes = array(
        'text' => 'activeTextField',
        'hidden' => 'activeHiddenField',
        'password' => 'activePasswordField',
        'textarea' => 'activeTextArea',
        'file' => 'activeFileField',
        'radio' => 'activeRadioButton',
        'checkbox' => 'activeCheckBox',
        'listbox' => 'activeListBox',
        'dropdownlist' => 'activeDropDownList',
        'checkboxlist' => 'activeCheckBoxList',
        'radiolist' => 'activeRadioButtonList',
        'url' => 'activeUrlField',
        'email' => 'activeEmailField',
        'number' => 'activeNumberField',
        'range' => 'activeRangeField',
        'date' => 'activeDateField',
        'read' => 'activeRead',
        'phones' => 'activePhones',//todo: FOR phones
    );
    public $afterContent;
    public $beforeContent;
    public $labelHint;
    public $labelHelp;

    /**
     * @var string the type of this input. This can be a widget class name, a path alias of a widget class name,
     * or an input type alias (text, hidden, password, textarea, file, radio, checkbox, listbox, dropdownlist, checkboxlist, or radiolist).
     * If a widget class, it must extend from {@link CInputWidget} or (@link CJuiInputWidget).
     */
    public $type;

    /**
     * @var string name of this input
     */
    public $name;

    /**
     * @var string hint text of this input
     */
    public $hint;

    /**
     * @var array the options for this input when it is a list box, drop-down list, check box list, or radio button list.
     * Please see {@link CHtml::listData} for details of generating this property value.
     */
    public $items = array();

    /**
     * @var array the options used when rendering the error part. This property will be passed
     * to the {@link CActiveForm::error} method call as its $htmlOptions parameter.
     * @see CActiveForm::error
     * @since 1.1.1
     */
    public $errorOptions = array();

    /**
     * @var boolean whether to allow AJAX-based validation for this input. Note that in order to use
     * AJAX-based validation, {@link CForm::activeForm} must be configured with 'enableAjaxValidation'=>true.
     * This property allows turning on or off  AJAX-based validation for individual input fields.
     * Defaults to true.
     * @since 1.1.7
     */
    public $enableAjaxValidation = true;

    /**
     * @var boolean whether to allow client-side validation for this input. Note that in order to use
     * client-side validation, {@link CForm::activeForm} must be configured with 'enableClientValidation'=>true.
     * This property allows turning on or off  client-side validation for individual input fields.
     * Defaults to true.
     * @since 1.1.7
     */
    public $enableClientValidation = true;

    /**
     * @var string the layout used to render label, input, hint and error. They correspond to the placeholders
     * "{label}", "{input}", "{hint}" and "{error}".
     */
    public $layout = "{label}\n{input}\n{hint}\n{error}";
    private $_label;
    private $_required;

    /**
     * Gets the value indicating whether this input is required.
     * If this property is not set explicitly, it will be determined by calling
     * {@link CModel::isAttributeRequired} for the associated model and attribute of this input.
     * @return boolean whether this input is required.
     */
    public function getRequired() {
        if ($this->_required !== null)
            return $this->_required;
        else
            return $this->getParent()->getModel()->isAttributeRequired($this->name);
    }

    /**
     * @param boolean $value whether this input is required.
     */
    public function setRequired($value) {
        $this->_required = $value;
    }

    /**
     * @return string the label for this input. If the label is not manually set,
     * this method will call {@link CModel::getAttributeLabel} to determine the label.
     */
    public function getLabel() {
        if ($this->_label !== null)
            return $this->_label;
        else
            return $this->getParent()->getModel()->getAttributeLabel($this->name);
    }

    /**
     * @param string $value the label for this input
     */
    public function setLabel($value) {
        $this->_label = $value;
    }

    /**
     * Renders everything for this input.
     * The default implementation simply returns the result of {@link renderLabel}, {@link renderInput},
     * {@link renderHint}. When {@link CForm::showErrorSummary} is false, {@link renderError} is also called
     * to show error messages after individual input fields.
     * @return string the complete rendering result for this input, including label, input field, hint, and error.
     */
    public function render() {

        if ($this->type === 'hidden')
            return $this->renderInput();
        $output = array(
            '{label}' => $this->renderLabel(),
            '{input}' => $this->renderInput(),
            '{hint}' => $this->renderHint(),
            '{error}' => !$this->getParent()->showErrors ? '' : $this->renderError(),
        );
        return strtr($this->layout, $output);
    }

    /**
     * Renders the label for this input.
     * The default implementation returns the result of {@link CHtml activeLabelEx}.
     * @return string the rendering result
     */
    public function renderLabel() {
        $options = array(
            'label' => $this->getLabel(),
            'required' => $this->getRequired(),
            'class' => 'col-sm-3 col-md-3 col-lg-2 col-form-label'
        );

        if (!empty($this->attributes['id'])) {
            $options['for'] = $this->attributes['id'];
        }
        if (isset($this->labelHint)) {
            $hint = '<div class="text-muted">' . $this->labelHint . '</div>';
        } else {
            $hint = '';
        }
        if (isset($this->labelHelp)) {
            $help = '<a href="javascript:void(0)" data-html="true" data-trigger="focus" data-toggle="popover" title="' . $this->getLabel() . '" data-placement="bottom" data-content="' . $this->labelHelp . '"><i class="label-help icon-info"></i></a>';
        } else {
            $help = '';
        }
        return CHtml::activeLabel($this->getParent()->getModel(), $this->name, $options) . $hint.$help;
    }

    public function setClassInput($method) {
        if (Yii::app()->controller instanceof AdminController) {
            if ($method == 'activeCheckBox') {

                if (isset($this->attributes['class'])) {
                    $this->attributes['class'] .= ' form-inline';
                } else {
                    $this->attributes['class'] = 'form-inline';
                }
            } elseif (strpos($method, 'dropDown') !== false) {
                if (isset($this->attributes['class'])) {
                    $this->attributes['class'] .= ' custom-select';
                } else {
                    $this->attributes['class'] = 'custom-select';
                }
            } elseif (strpos($method, 'CheckBox') !== false) {
                if (isset($this->attributes['class'])) {
                    $this->attributes['class'] .= ' checkbox';
                } else {
                    $this->attributes['class'] = 'checkbox';
                }
            } elseif (strpos($method, 'RadioButton') !== false) {
                if (isset($this->attributes['class'])) {
                    $this->attributes['class'] .= ' radio';
                } else {
                    $this->attributes['class'] = 'radio';
                }
			} elseif (strpos($method, 'File') !== false) {
                if (isset($this->attributes['class'])) {
                    $this->attributes['class'] .= ' ';
                } else {
                    $this->attributes['class'] = '';
                }
            } else {
                if (isset($this->attributes['class'])) {
                    $this->attributes['class'] .= ' form-control';
                } else {
                    $this->attributes['class'] = 'form-control';
                }
            }
        }
    }

    /**
     * Renders the input field.
     * The default implementation returns the result of the appropriate CHtml method or the widget.
     * @return string the rendering result
     */
    public function renderInput() {

        $model = $this->getParent()->getModel();
        $name = $this->name;

        if (isset(self::$coreTypes[$this->type])) {
            $method = self::$coreTypes[$this->type];
            $this->setClassInput($method);

            if ($method == 'activeRead') {
                return '<div>' . $model->$name . '</div>';
            }elseif($method == 'activePhones') {
                $this->afterContent = 'ss';
                //todo: FOR phones
                Yii::app()->clientScript->registerScript('asd'.Html::getIdByName($name),'
                
                $("#addd").click(function(){
                 var row = $(".field_phone .copyPhone").clone().removeClass("copyPhone");
                 $(".field_phone .col-sm-9").append(row);
                });
                
                ');
                return $this->overlayInput(Html::activeTextField($model, $name.'[]', $this->attributes),'copyPhone').'<a href="javascript:void(0)" id="addd">add</a>';
            }else {
                if (strpos($method, 'List') !== false) {
                    return $this->overlayInput(Html::$method($model, $name, $this->items, $this->attributes));
                } else {
                    return $this->overlayInput(Html::$method($model, $name, $this->attributes));
                }
            }
        } else {
            $attributes = $this->attributes;
            $attributes['model'] = $model;
            $attributes['attribute'] = $name;
            ob_start();
            echo $this->afterContent;
            $this->getParent()->getOwner()->widget($this->type, $attributes);
            echo $this->beforeContent;
            return ob_get_clean();
        }
    }

    private function overlayInput($input, $class=false) {
        if($class){//todo: FOR phones
            $mclass=$class;
        }else{
            $mclass='';
        }
        if (!empty($this->afterContent) || !empty($this->beforeContent)) {
            if(preg_match("/input|select/", $this->afterContent, $m)){
                $this->afterContent = (!empty($this->afterContent)) ? '<div class="input-group-prepend">'.$this->afterContent.'</div>' : '';
            }else{
                $this->afterContent = (!empty($this->afterContent)) ? '<div class="input-group-append">'.Html::tag('div', array('class' => 'input-group-text'), $this->afterContent, true).'</div>' : '';
            }

            if(preg_match("/input|select/", $this->beforeContent, $m)){
                $this->beforeContent = (!empty($this->beforeContent)) ? '<div class="input-group-prepend">'.$this->beforeContent.'</div>' : '';
            }else{
                $this->beforeContent = (!empty($this->beforeContent)) ? '<div class="input-group-prepend">'.Html::tag('div', array('class' => 'input-group-text'), $this->beforeContent, true).'</div>' : '';
            }
            return Html::tag('div', array('class' => 'input-group '.$mclass), $this->beforeContent . $input . $this->afterContent, true);
        } else {
            return $input;
        }
    }

    /**
     * Renders the error display of this input.
     * The default implementation returns the result of {@link CHtml::error}
     * @return string the rendering result
     */
    public function renderError() {
        $parent = $this->getParent();
        return $parent->getActiveFormWidget()->error($parent->getModel(), $this->name, $this->errorOptions, $this->enableAjaxValidation, $this->enableClientValidation);
    }

    /**
     * Renders the hint text for this input.
     * The default implementation returns the {@link hint} property enclosed in a paragraph HTML tag.
     * @return string the rendering result.
     */
    public function renderHint() {
        return $this->hint === null ? '' : '<span class="form-text text-muted">' . $this->hint . '</span>';
    }

    /**
     * Evaluates the visibility of this element.
     * This method will check if the attribute associated with this input is safe for
     * the current model scenario.
     * @return boolean whether this element is visible.
     */
    protected function evaluateVisible() {
        return $this->getParent()->getModel()->isAttributeSafe($this->name);
    }

}
