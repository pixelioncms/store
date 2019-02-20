<?php

class ActiveForm extends CActiveForm
{
    public $errorMessageCssClass = 'invalid-feedback'; //bootstrap 4
    private $successCssClass = 'is-valid'; //bootstrap 4
    private $errorCssClass = 'is-invalid'; //bootstrap 4

    public function init()
    {
        if (!isset($this->clientOptions['successCssClass'])) {
            $this->clientOptions['successCssClass'] = $this->successCssClass;
        }
        if (!isset($this->clientOptions['errorCssClass'])) {
            $this->clientOptions['errorCssClass'] = $this->errorCssClass;
        }

        parent::init();

    }
}