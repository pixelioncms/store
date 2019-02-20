<?php

/**
 * Base class for configuration delivery forms.
 * Renders form without <form> tags.
 */
class BaseDeliveryForm extends CMSForm {

    public function render() {
        $this->renderBegin();
        $form = $this->renderBody();
        $this->renderEnd();

        return $form;
    }

}