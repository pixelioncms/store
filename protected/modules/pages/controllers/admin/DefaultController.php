<?php

/**
 * Контроллер админ-панели статичных страниц
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.pages.controllers.admin
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
        );
    }

    /**
     * Display pages list.
     */
    public function actionIndex() {
        $this->pageName = $this->module->name;
        $this->breadcrumbs = array($this->pageName);
        $model = new Page('search');
        $model->unsetAttributes();
        if (!empty($_GET['Page']))
            $model->attributes = $_GET['Page'];

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
            $model = new Page;
        } else {
            $model = Page::model()
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404);

        

        $this->breadcrumbs = array(
            $this->module->name => $this->createUrl('index'),
            ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : CHtml::encode($model->title),
        );

        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : $model::t('PAGE_TITLE', 1);
        
        $form = new TabForm($model->getForm(), $model);
        $form->additionalTabs[Yii::t('app','TAB_META')] = array(
            'content' => $this->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $model,'form'=>$form), true)
            );
        
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Page'];
            if ($model->validate()) {
                $model->save();
                if ($model->in_menu == 1) {
                    $isset = MenuModel::model()->findByAttributes(array('url' => '/page/' . $model->seo_alias));
                    if (!isset($isset)) {
                        $menu = new MenuModel;
                       // $menu->label = $model->title;
                        $menu->url = '/page/' . $model->seo_alias;
                        if ($menu->validate()) {
                            $menu->save(false,false);
                        }
                    }
                }

                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model,'form'=>$form));
    }

}

