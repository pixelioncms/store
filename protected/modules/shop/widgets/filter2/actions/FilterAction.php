<?php

/**
 * CallbackAction class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage callback.actions
 * @uses CAction
 * 
 * @property array $receiverMail Массив e-mails
 */
class FilterAction extends CAction {

    public $attributes;
    public $model;

    public function run() {

        if (Yii::app()->request->isAjaxRequest) {

            print_r($this->attributes);die;

            Yii::app()->controller->widget('mod.shop.widgets.filter.FilterWidget', array(
                'model' => $this->model,
                'attributes' => $this->attributes,
                'countAttr' => true,
                'countManufacturer' => true,
                    ));
            /* $this->controller->render('mod.shop.widgets.filter.views.default', array(
              'sended' => $sended,
              'path'=>'mod.shop.widgets.filter.views.',
              'model'=>$this->model,
              'attributes'=>$this->attributes
              )); */
        } else {
            throw new CHttpException(403);
        }
    }

}
