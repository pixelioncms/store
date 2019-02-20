<?php

/**
 * Install to main/AjaxController actions 
 * public function actions() {
 *      return array(
 *          ...
 *          'subscribe.' => 'mod.delivery.widgets.subscribe.SubscribeWidget',
 *      );
 *  }
 * @return URL /ajax/subscribe.action
 * 
 */
class SubscribeWidget extends Widget {

    /**
     * Action
     */
    public static function actions() {
        return array(
            'action' => array(
                'class'=>'mod.delivery.widgets.subscribe.actions.SubscribeAction',
            ),
        );
    }

    public function init()
    {

        parent::init();
        Yii::app()->clientScript->registerScript($this->getId(),'function subscribeSubmit(formid, reload) {
        var str = $(formid).serialize();
        str +="&skin='.$this->skin.'";
        $.ajax({
            url: $(formid).attr("action"),
            type: "POST",
            data: str,
            success: function (data) {
                $(reload).html(data);
            },
            complete: function () {

            }
        });
    }',CClientScript::POS_END);
    }

    /**
     * Run widget
     */
    public function run() {

        if (Yii::app()->user->isGuest && Yii::app()->hasModule('delivery')) {
            Yii::import('mod.delivery.DeliveryModule');
            Yii::import('mod.delivery.models.Delivery');
            $model = new Delivery();

            $this->render($this->skin, array('model' => $model));
        }
    }

}

?>
