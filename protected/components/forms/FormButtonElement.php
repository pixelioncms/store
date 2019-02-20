<?php

/**
 * CFormButtonElement class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @package components
 */

/**
 * CFormButtonElement represents a form button element.
 *
 * CFormButtonElement can represent the following types of button based on {@link type} property:
 * <ul>
 * <li>htmlButton: a normal button generated using {@link CHtml::htmlButton}</li>
 * <li>htmlReset a reset button generated using {@link CHtml::htmlButton}</li>
 * <li>htmlSubmit: a submit button generated using {@link CHtml::htmlButton}</li>
 * <li>submit: a submit button generated using {@link CHtml::submitButton}</li>
 * <li>button: a normal button generated using {@link CHtml::button}</li>
 * <li>image: an image button generated using {@link CHtml::imageButton}</li>
 * <li>reset: a reset button generated using {@link CHtml::resetButton}</li>
 * <li>link: a link button generated using {@link CHtml::linkButton}</li>
 * </ul>
 * The {@link type} property can also be a class name or a path alias to the class. In this case,
 * the button is generated using a widget of the specified class. Note, the widget must
 * have a property called "name".
 *
 * Because CFormElement is an ancestor class of CFormButtonElement, a value assigned to a non-existing property will be
 * stored in {@link attributes} which will be passed as HTML attribute values to the {@link CHtml} method
 * generating the button or initial values of the widget properties.
 *
 * @property string $on Scenario names separated by commas. Defaults to null.
 *
 * @since 1.1
 */
class FormButtonElement extends CFormButtonElement {

    /**
     * @var array Core button types (alias=>CHtml method name)
     */
    public static $coreTypes = array(
        'htmlButton' => 'htmlButton',
        'htmlSubmit' => 'htmlButton',
        'htmlReset' => 'htmlButton',
        'button' => 'button',
        'submit' => 'submitButton',
        'ajaxSubmit' => 'ajaxSubmitButton',
        'reset' => 'resetButton',
        'image' => 'imageButton',
        'link' => 'linkButton',
    );

    /**
     * @var string the type of this button. This can be a class name, a path alias of a class name,
     * or a button type alias (submit, button, image, reset, link, htmlButton, htmlSubmit, htmlReset).
     */
    public $type;

    /**
     * @var string name of this button
     */
    public $name;

    /**
     * @var string the label of this button. This property is ignored when a widget is used to generate the button.
     */
    public $label;
    private $_on;

    /**
     * Returns this button.
     * @return string the rendering result
     */
    public function render() {
        $attributes = $this->attributes;

        if (isset(self::$coreTypes[$this->type])) {
            $method = self::$coreTypes[$this->type];
            if ($method === 'linkButton') {
                if (!isset($attributes['params'][$this->name]))
                    $attributes['params'][$this->name] = 1;
            }
            elseif ($method === 'htmlButton') {
                $attributes['type'] = $this->type === 'htmlSubmit' ? 'submit' : ($this->type === 'htmlReset' ? 'reset' : 'button');
                $attributes['name'] = $this->name;
            } else
                $attributes['name'] = $this->name;
            if ($method === 'imageButton') {
                return CHtml::imageButton(isset($attributes['src']) ? $attributes['src'] : '', $attributes);
            } elseif ($method === 'ajaxSubmitButton') {    //no work and no test;
                $htmlOptions = array();
                if (isset($attributes['class'])) {
                    $htmlOptions['class'] = $attributes['class'];
                }
                if (!isset($attributes['dataType'])) {
                    $attributes['dataType'] = 'html';
                }
                $attributes['ajaxUrl'] = $this->name;
                var_dump($htmlOptions);
                return CHtml::ajaxSubmitButton($this->label, $attributes['params']['ajaxUrl'], array(
                            'type' => 'POST',
                            'dataType' => $attributes['dataType'],
                            'update' => $attributes['update'],
                            'success' => 'js:function(data){
                                if(data.status === "success"){
                                    common.notify(data.message,"success");
                                }
                            }',
                            'beforeSend' => 'js:function(data){
                                common.addLoader("hgah");
                            }',
                            'complete' => 'js:function(data){
                                common.removeLoader();
                            }'
                                ), array(
                            'type' => 'submit',
                            'class' => $attributes['class']
                ));
            } else {

                return CHtml::$method($this->label, $attributes);
            }
        } else {
            $attributes['name'] = $this->name;
            ob_start();
            $this->getParent()->getOwner()->widget($this->type, $attributes);
            return ob_get_clean();
        }
    }

}
