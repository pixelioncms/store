<?php

/**
 * CMSForm class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class CMSForm extends CFormElement implements ArrayAccess
{

    /**
     * @var string the title for this form. By default, if this is set, a fieldset may be rendered
     * around the form body using the title as its legend. Defaults to null.
     */
    public $title;

    /**
     * @var string the description of this form.
     */
    public $description;

    /**
     * @var string the submission method of this form. Defaults to 'post'.
     * This property is ignored when this form is a sub-form.
     */
    public $method = 'post';

    /**
     * @var mixed the form action URL (see {@link CHtml::normalizeUrl} for details about this parameter.)
     * Defaults to an empty string, meaning the current request URL.
     * This property is ignored when this form is a sub-form.
     */
    public $action = '';

    /**
     * @var string the name of the class for representing a form input element. Defaults to 'CFormInputElement'.
     */
    public $inputElementClass = 'FormInputElement';

    /**
     * @var string the name of the class for representing a form button element. Defaults to 'CFormButtonElement'.
     */
    public $buttonElementClass = 'FormButtonElement';

    /**
     * @var array HTML attribute values for the form tag. When the form is embedded within another form,
     * this property will be used to render the HTML attribute values for the fieldset enclosing the child form.
     */
    public $attributes = array();

    /**
     * @var boolean whether to show error summary. Defaults to false.
     */
    public $showErrorSummary = false;

    /**
     * @var boolean|null whether error elements of the form attributes should be rendered. There are three possible
     * valid values: null, true and false.
     *
     * Defaults to null meaning that {@link $showErrorSummary} will be used as value. This is done mainly to keep
     * backward compatibility with existing applications. If you want to use error summary with AJAX and/or client
     * validation you have to set this property to true (recall that {@link CActiveForm::error()} should be called
     * for each attribute that is going to be AJAX and/or client validated).
     *
     * False value means that the error elements of the form attributes shall not be displayed. True value means that
     * the error elements of the form attributes will be rendered.
     *
     * @since 1.1.14
     */
    public $showErrors;

    /**
     * @var array the configuration used to create the active form widget.
     * The widget will be used to render the form tag and the error messages.
     * The 'class' option is required, which specifies the class of the widget.
     * The rest of the options will be passed to {@link CBaseController::beginWidget()} call.
     * Defaults to array('class'=>'CActiveForm').
     * @since 1.1.1
     */
    public $activeForm = array('class' => 'CActiveForm');
    private $_model;
    private $_elements;
    private $_buttons;
    private $_activeForm;

    /**
     * Constructor.
     * If you override this method, make sure you do not modify the method
     * signature, and also make sure you call the parent implementation.
     * @param mixed $config the configuration for this form. It can be a configuration array
     * or the path alias of a PHP script file that returns a configuration array.
     * The configuration array consists of name-value pairs that are used to initialize
     * the properties of this form.
     * @param CModel $model the model object associated with this form. If it is null,
     * the parent's model will be used instead.
     * @param mixed $parent the direct parent of this form. This could be either a {@link CBaseController}
     * object (a controller or a widget), or a {@link CForm} object.
     * If the former, it means the form is a top-level form; if the latter, it means this form is a sub-form.
     */
    public function __construct($config, $model = null, $parent = null)
    {
        $this->setModel($model);
        if ($parent === null)
            $parent = Yii::app()->getController();
        parent::__construct($config, $parent);
        if ($this->showErrors === null)
            $this->showErrors = !$this->showErrorSummary;
        $this->init();
    }

    /**
     * Initializes this form.
     * This method is invoked at the end of the constructor.
     * You may override this method to provide customized initialization (such as
     * configuring the form object).
     */
    protected function init()
    {

    }

    /**
     * Returns a value indicating whether this form is submitted.
     * @param string $buttonName the name of the submit button
     * @param boolean $loadData whether to call {@link loadData} if the form is submitted so that
     * the submitted data can be populated to the associated models.
     * @return boolean whether this form is submitted.
     * @see loadData
     */
    public function submitted($buttonName = 'submit', $loadData = true)
    {
        $ret = $this->clicked($this->getUniqueId()) && $this->clicked($buttonName);
        if ($ret && $loadData)
            $this->loadData();
        return $ret;
    }

    /**
     * Returns a value indicating whether the specified button is clicked.
     * @param string $name the button name
     * @return boolean whether the button is clicked.
     */
    public function clicked($name)
    {
        if (strcasecmp($this->getRoot()->method, 'get'))
            return isset($_POST[$name]);
        else
            return isset($_GET[$name]);
    }

    /**
     * Validates the models associated with this form.
     * All models, including those associated with sub-forms, will perform
     * the validation. You may use {@link CModel::getErrors()} to retrieve the validation
     * error messages.
     * @return boolean whether all models are valid
     */
    public function validate()
    {
        $ret = true;
        foreach ($this->getModels() as $model)
            $ret = $model->validate() && $ret;
        return $ret;
    }

    /**
     * Loads the submitted data into the associated model(s) to the form.
     * This method will go through all models associated with this form and its sub-forms
     * and massively assign the submitted data to the models.
     * @see submitted
     */
    public function loadData()
    {
        if ($this->_model !== null) {
            $class = CHtml::modelName($this->_model);
            if (strcasecmp($this->getRoot()->method, 'get')) {
                if (isset($_POST[$class]))
                    $this->_model->setAttributes($_POST[$class]);
            } elseif (isset($_GET[$class]))
                $this->_model->setAttributes($_GET[$class]);
        }
        foreach ($this->getElements() as $element) {
            if ($element instanceof self)
                $element->loadData();
        }
    }

    /**
     * @return CForm the top-level form object
     */
    public function getRoot()
    {
        $root = $this;
        while ($root->getParent() instanceof self)
            $root = $root->getParent();
        return $root;
    }

    /**
     * @return CActiveForm the active form widget associated with this form.
     * This method will return the active form widget as specified by {@link activeForm}.
     * @since 1.1.1
     */
    public function getActiveFormWidget()
    {
        if ($this->_activeForm !== null)
            return $this->_activeForm;
        else
            return $this->getRoot()->_activeForm;
    }

    /**
     * @return CBaseController the owner of this form. This refers to either a controller or a widget
     * by which the form is created and rendered.
     */
    public function getOwner()
    {
        $owner = $this->getParent();
        while ($owner instanceof self)
            $owner = $owner->getParent();
        return $owner;
    }

    /**
     * Returns the model that this form is associated with.
     * @param boolean $checkParent whether to return parent's model if this form doesn't have model by itself.
     * @return CModel the model associated with this form. If this form does not have a model,
     * it will look for a model in its ancestors.
     */
    public function getModel($checkParent = true)
    {
        if (!$checkParent)
            return $this->_model;
        $form = $this;
        while ($form->_model === null && $form->getParent() instanceof self)
            $form = $form->getParent();
        return $form->_model;
    }

    /**
     * @param CModel $model the model to be associated with this form
     */
    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Returns all models that are associated with this form or its sub-forms.
     * @return array the models that are associated with this form or its sub-forms.
     */
    public function getModels()
    {
        $models = array();
        if ($this->_model !== null)
            $models[] = $this->_model;
        foreach ($this->getElements() as $element) {
            if ($element instanceof self)
                $models = array_merge($models, $element->getModels());
        }
        return $models;
    }

    /**
     * Returns the input elements of this form.
     * This includes text strings, input elements and sub-forms.
     * Note that the returned result is a {@link CFormElementCollection} object, which
     * means you can use it like an array. For more details, see {@link CMap}.
     * @return CFormElementCollection the form elements.
     */
    public function getElements()
    {


        if ($this->_elements === null)
            $this->_elements = new CFormElementCollection($this, false);
        return $this->_elements;
    }

    /**
     * Configures the input elements of this form.
     * The configuration must be an array of input configuration array indexed by input name.
     * Each input configuration array consists of name-value pairs that are used to initialize
     * a {@link CFormStringElement} object (when 'type' is 'string'), a {@link CFormElement} object
     * (when 'type' is a string ending with 'Form'), or a {@link CFormInputElement} object in
     * all other cases.
     * @param array $elements the elements configurations
     */
    public function setElements($elements)
    {
        $collection = $this->getElements();

        foreach ($elements as $name => $config){
            //echo CVarDumper::dump($config,10,true);die;
                //if(count($config) > 1){
                //    foreach ($config as $n => $c){
                //        $collection->add($n, $c);
                //    }
                //}else{
                    $collection->add($name, $config);
               // }


        }
    }

    /**
     * Returns the button elements of this form.
     * Note that the returned result is a {@link CFormElementCollection} object, which
     * means you can use it like an array. For more details, see {@link CMap}.
     * @return CFormElementCollection the form elements.
     */
    public function getButtons()
    {
        if ($this->_buttons === null)
            $this->_buttons = new CFormElementCollection($this, true);
        return $this->_buttons;
    }

    /**
     * Configures the buttons of this form.
     * The configuration must be an array of button configuration array indexed by button name.
     * Each button configuration array consists of name-value pairs that are used to initialize
     * a {@link CFormButtonElement} object.
     * @param array $buttons the button configurations
     */
    public function setButtons($buttons)
    {
        $collection = $this->getButtons();
        foreach ($buttons as $name => $config)
            $collection->add($name, $config);
    }

    /**
     * Renders the form.
     * The default implementation simply calls {@link renderBegin}, {@link renderBody} and {@link renderEnd}.
     * @return string the rendering result
     */
    public function render()
    {
        return $this->renderBegin() . $this->renderBody() . $this->renderEnd();
    }

    /**
     * Renders the open tag of the form.
     * The default implementation will render the open form tag.
     * @return string the rendering result
     */
    public function renderBegin()
    {
        if ($this->getParent() instanceof self)
            return '';
        else {


            $options = $this->activeForm;
            if (isset($options['class'])) {
                $class = $options['class'];
                unset($options['class']);
            } else
                $class = 'CActiveForm';
            $options['action'] = $this->action;
            $options['method'] = $this->method;
            if (isset($options['htmlOptions'])) {
                foreach ($this->attributes as $name => $value)
                    $options['htmlOptions'][$name] = $value;
            } else
                $options['htmlOptions'] = $this->attributes;


            if (Yii::app()->request->isPostRequest && !$this->getModel()->validate()) {
                if (isset($options['htmlOptions']['class'])) {
                    $options['htmlOptions']['class'] .= $this->attributes['class'] . ' form-error';
                } else {
                    $options['htmlOptions']['class'] = 'form-error';
                }
            }

            ob_start();
            $this->_activeForm = $this->getOwner()->beginWidget($class, $options);
            return ob_get_clean() . "<div style=\"visibility:hidden\">" . CHtml::hiddenField($this->getUniqueID(), 1) . "</div>\n";
        }
    }

    /**
     * Renders the close tag of the form.
     * @return string the rendering result
     */
    public function renderEnd()
    {
        if ($this->getParent() instanceof self)
            return '';
        else {
            ob_start();
            $this->getOwner()->endWidget();
            return ob_get_clean();
        }
    }

    /**
     * Renders the body content of this form.
     * This method mainly renders {@link elements} and {@link buttons}.
     * If {@link title} or {@link description} is specified, they will be rendered as well.
     * And if the associated model contains error, the error summary may also be displayed.
     * The form tag will not be rendered. Please call {@link renderBegin} and {@link renderEnd}
     * to render the open and close tags of the form.
     * You may override this method to customize the rendering of the form.
     * @return string the rendering result
     */
    public function renderBody()
    {
        $output = '';
        if ($this->title !== null) {
            if ($this->getParent() instanceof self) {
                $attributes = $this->attributes;
                unset($attributes['name'], $attributes['type']);
                $output = CHtml::openTag('div', $attributes) . "<legend>" . $this->title . "</legend>\n";
            } else
                $output = "<fieldset>\n<legend>" . $this->title . "</legend>\n";
        }

        if ($this->description !== null)
            $output .= "<div class=\"description\">\n" . $this->description . "</div>\n";

        if ($this->showErrorSummary && ($model = $this->getModel(false)) !== null) {
            $errorSummary = $this->getActiveFormWidget()->errorSummary($model, null, null, array('class' => CHtml::$errorSummaryCss . ' alert alert-danger'));
            if (Yii::app()->controller instanceof AdminController) {
                if ($errorSummary)
                    $output .= $errorSummary . "\n";
            } else {
                $output .= $errorSummary . "\n";
            }
        }

        $output .= $this->renderElements() . "\n" . $this->renderButtons() . "\n";

        if ($this->title !== null)
            $output .= "</div>\n";

        return $output;
    }

    /**
     * Renders the {@link elements} in this form.
     * @return string the rendering result
     */
    public function renderElements()
    {
        $output = '';
        foreach ($this->getElements() as $element) {
            //echo CVarDumper::dump($element,4,true);die;
            $output .= $this->renderElement($element);
        }
        return $output;
    }

    /**
     * Renders the {@link buttons} in this form.
     * @return string the rendering result
     */
    public function renderButtons()
    {

        $output = '';
        foreach ($this->getButtons() as $button)
            $output .= $this->renderElement($button);
        return $output !== '' ? "<div class=\"form-group row buttons text-center\"><div class=\"col-md-12\">" . $output . "</div></div>\n" : '';
    }

    /**
     * Renders a single element which could be an input element, a sub-form, a string, or a button.
     * @param mixed $element the form element to be rendered. This can be either a {@link CFormElement} instance
     * or a string representing the name of the form element.
     * @return string the rendering result
     */
    public function renderElement($element)
    {
        if (is_string($element)) {
            if (($e = $this[$element]) === null && ($e = $this->getButtons()->itemAt($element)) === null)
                return $element;
            else
                $element = $e;
        }
if(is_array($element)){
            die('array');
}
        if ($element->getVisible()) {
            if ($element instanceof FormInputElement) {
                $render = '';
                if (isset($element->help)) {
                    $helpButton = Html::tag('span', array(
                        // 'class'=>'btn btn-primary',
                        //'style'=>'cursor:help',
                        'data-container' => 'body',
                        'data-placement' => 'left',
                        'data-toggle' => 'popover',
                        //'title'=>'Помощь:',
                        'data-content' => $element->help
                    ), '<i class="icon-question"></i>', true);

                    $render .= Html::tag('div', array('class' => 'input-group'));
                    $render .= $element->renderInput();
                    if (in_array($element->type, array('text'))) {
                        $render .= Html::tag('span', array('class' => 'input-group-addon'), $helpButton, true);
                        $render .= Html::closeTag('div');
                    } else {
                        $render .= Html::tag('span', array('class' => 'input-group-addon input-group-addon-transparent'), $helpButton, true);
                        $render .= Html::closeTag('div');
                    }
                } else {
                    $render .= $element->renderInput();
                }

                if ($element->type === 'hidden')
                    return "<div style=\"visibility:hidden\">\n" . $element->render() . "</div>\n";
                else
                    return "<div class=\"form-group row field_{$element->name}\">" . $element->renderLabel() . "<div class=\"col-sm-9 col-md-9 col-lg-10\">" . $render . "" . $element->renderHint() . "" . $element->renderError() . "</div></div>\n";
            } elseif ($element instanceof FormButtonElement)
                return $element->render() . "\n";
            else
                return $element->render();
        }
        return '';
    }

    /**
     * This method is called after an element is added to the element collection.
     * @param string $name the name of the element
     * @param CFormElement $element the element that is added
     * @param boolean $forButtons whether the element is added to the {@link buttons} collection.
     * If false, it means the element is added to the {@link elements} collection.
     */
    public function addedElement($name, $element, $forButtons)
    {

    }

    /**
     * This method is called after an element is removed from the element collection.
     * @param string $name the name of the element
     * @param CFormElement $element the element that is removed
     * @param boolean $forButtons whether the element is removed from the {@link buttons} collection
     * If false, it means the element is removed from the {@link elements} collection.
     */
    public function removedElement($name, $element, $forButtons)
    {

    }

    /**
     * Evaluates the visibility of this form.
     * This method will check the visibility of the {@link elements}.
     * If any one of them is visible, the form is considered as visible. Otherwise, it is invisible.
     * @return boolean whether this form is visible.
     */
    protected function evaluateVisible()
    {
        foreach ($this->getElements() as $element)
            if ($element->getVisible())
                return true;
        return false;
    }

    /**
     * Returns a unique ID that identifies this form in the current page.
     * @return string the unique ID identifying this form
     */
    protected function getUniqueId()
    {
        if (isset($this->attributes['id']))
            return 'yform_' . $this->attributes['id'];
        else
            return 'yform_' . sprintf('%x', crc32(serialize(array_keys($this->getElements()->toArray()))));
    }

    /**
     * Returns whether there is an element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param mixed $offset the offset to check on
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->getElements()->contains($offset);
    }

    /**
     * Returns the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to retrieve element.
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    public function offsetGet($offset)
    {
        return $this->getElements()->itemAt($offset);
    }

    /**
     * Sets the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to set element
     * @param mixed $item the element value
     */
    public function offsetSet($offset, $item)
    {
        $this->getElements()->add($offset, $item);
    }

    /**
     * Unsets the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param mixed $offset the offset to unset element
     */
    public function offsetUnset($offset)
    {
        $this->getElements()->remove($offset);
    }

}
