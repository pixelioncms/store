<?php

/**
 * Контроллер админ-панели banner
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.banner.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => Banner::model(),
            ),
            'removefile' => array(
                'class' => 'ext.bootstrap.fileinput.actions.RemoveFileAction',
                'model' => Banner::model(),
                'dir' => 'banner',
                'attribute' => 'image'
            ),
        );
    }

    /**
     * Display pages list.
     */
    public function actionIndex() {
        $this->pageName = Yii::t('BannerModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName);
        $model = new Banner('search');
        if (!empty($_GET['Banner']))
            $model->attributes = $_GET['Banner'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Create or update new page
     * @param boolean $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new Banner;
        } else {
            $model = Banner::model()
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404);

        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : $model::t('PAGE_TITLE', 1);

        $this->breadcrumbs = array(
            Yii::t('BannerModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            $this->pageName
        );

        // $oldImage = $model->image;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Banner'];
            if ($model->validate()) {
                $model->save();

              //  $this->redirect(array('index'));
                $this->refresh();
            }
        }
        $this->render('update', array('model' => $model));
    }


}
