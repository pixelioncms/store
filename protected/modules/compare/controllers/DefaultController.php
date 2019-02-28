<?php

Yii::import('mod.compare.components.CompareProducts');

/**
 * Compare products controller
 */
class DefaultController extends Controller {

    /**
     * @var CompareProducts
     */
    public $model;

    public function beforeAction($action) {
        $this->model = new CompareProducts;
        return true;
    }

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * Render index view
     */
    public function actionIndex($cat_id = false) {

        $this->pageName = Yii::t('CompareModule.default', 'MODULE_NAME');

        $this->breadcrumbs = array($this->pageName);
        $compareForm = new CompareForm;
        if (isset($_POST['CompareForm']))
            $compareForm->attributes = $_POST['CompareForm'];

        if (!$cat_id && isset($this->model->products)) {
            foreach ($this->model->products as $id => $group) {
                $cat_id = $id;
                break;
            }
        }


        $this->render(CMS::isModile() ? 'mobile_index' : 'index', array('compareForm' => $compareForm,'cat_id'=>$cat_id));
    }

    /**
     * Add product to compare list
     * @param $id ShopProduct id
     */
    public function actionAdd($id) {
        $this->model->add($id);
        $message = Yii::t('CompareModule.default', 'SUCCESS_ADD');
        $this->setNotify($message);
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect($this->createUrl('index'));
        } else {
            echo CJSON::encode(array(
                'message' => $message,
                'btn_message' => Yii::t('CompareModule.default', 'BTN_COMPARE', 1),
                'count' => $this->model->count()
            ));
        }
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
