<?php

Yii::import('mod.wishlist.components.WishListComponent');

/**
 * Display products added to wish list
 */
class DefaultController extends Controller {

    /**
     * @var Wishlist
     */
    public $model;

    /**
     * @param CAction $action
     * @return bool
     * @throws CHttpException
     */
    public function beforeAction($action) {
    /*    if (Yii::app()->user->isGuest && $this->action->id !== 'view') {
            Yii::app()->user->returnUrl = Yii::app()->request->getUrl();
            if (Yii::app()->request->isAjaxRequest)
                throw new CHttpException(302);
            else
                $this->redirect(Yii::app()->user->loginUrl);
        }*/

        $this->model = new WishListComponent();
        return parent::beforeAction($action);
    }

    /**
     * Render index view
     */
    public function actionIndex() {
        $this->pageName = Yii::t('WishlistModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName => array('/wishlist'));
        $this->render('index');
    }

    /**
     * Add product to wish list
     * @param $id ShopProduct id
     */
    public function actionAdd($id) {

        $this->model->add($id);
        $message = Yii::t('WishlistModule.default', 'SUCCESS_ADD');
        $this->addFlashMessage($message);
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect($this->createUrl('index'));
        } else {
            echo CJSON::encode(array(
                'message' => $message,
                'btn_message'=>Yii::t('WishlistModule.default','BTN_WISHLIST',1),
                'count' => $this->model->count()
            ));
            Yii::app()->end();
        }
    }

    /**
     * @param $key
     * @throws CHttpException
     */
    public function actionView($key) {
        try {
            $this->model->loadByKey($key);
        } catch (CException $e) {
            throw new CHttpException(404, Yii::t('WishlistModule.default', 'ERROR_VIEW'));
        }

        $this->render('index');
    }

    /**
     * Remove product from list
     * @param string $id product id
     */
    public function actionRemove($id) {
        $this->model->remove($id);
        if (!Yii::app()->request->isAjaxRequest)
            $this->redirect($this->createUrl('index'));
    }

}
