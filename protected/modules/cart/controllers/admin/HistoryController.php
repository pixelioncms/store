<?php

class HistoryController extends AdminController {

    public $topButtons = false;



    public function actionIndex() {
        $model = new OrderProduct('search');
        $form = new OrderProductHistoryForm;
        $buttons = array();
        $buttons[] = array(
            'label' => Yii::t('CartModule.admin', 'HISTORY_RESET'),
            'url' => '/admin/cart/history',
            'htmlOptions' => array('class' => 'btn btn-primary')
        );
        if (Yii::app()->hasComponent('pdf')) {
            $buttons[] = array(
                'label' => Yii::t('CartModule.admin', 'SAVE_PDF'),
                'url' => '#',
                'htmlOptions' => array('class' => 'btn btn-success', 'id' => 'save_pdf', 'target' => '_blank')
            );
        }

        $this->topButtons = $buttons;
        $this->pageName = Yii::t('CartModule.admin', 'ORDERED_PRODUCTS');
        if (!empty($_GET['OrderProduct']))
            $model->attributes = $_GET['OrderProduct'];

        if (isset($_GET['OrderProductHistoryForm'])) {
            $form->attributes = $_GET['OrderProductHistoryForm'];

            if ($form->validate()) {
                //$this->setNotify(Yii::t('app', 'OK'));
            } else {
                print_r($form->getErrors());
                $this->setNotify(Yii::t('app', 'NO VALID'));
            }
        }
        $this->render('index', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    /*
      public function actionNew() {
      $model = new OrderProduct('search');

      $form = new OrderProductHistoryForm;

      $this->pageName = Yii::t('CartModule.admin', 'Заказанные продукты');
      if (!empty($_GET['OrderProduct']))
      $model->attributes = $_GET['OrderProduct'];

      if (isset($_GET['OrderProductHistoryForm'])) {
      $form->attributes = $_GET['OrderProductHistoryForm'];

      if ($form->validate()) {
      //$this->setNotify(Yii::t('app', 'OK'));
      } else {
      print_r($form->getErrors());
      $this->setNotify(Yii::t('app', 'NO VALID'));
      }
      }
      $this->render('index', array(
      'model' => $model,
      'form' => $form,
      ));
      }
     */

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                'url' => array('/admin/cart'),
                'icon' => Html::icon('icon-cart'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Index')),
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATUSES'),
                'url' => array('/admin/cart/statuses'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Statuses.*', 'Cart.Statuses.Index')),
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATS'),
                'url' => array('/admin/cart/statistics'),
                'icon' => Html::icon('icon-stats'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Statistics.*', 'Cart.Statistics.Index')),
            ),
        );
    }

}
