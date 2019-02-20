<?php

/**
 * Base class for configuration widget forms.
 * Renders form without <form> tags.
 */
class WidgetForm extends CMSForm {

    public function render() {

        $form = $this->renderBegin();
        $form .= $this->renderBody();
        $form .= $this->renderEnd();
        return $form;
    }

}