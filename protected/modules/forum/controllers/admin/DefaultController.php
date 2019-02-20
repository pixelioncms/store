<?php

/**
 * Контроллер админ-панели статичных страниц
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.news.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {



    public function actionIndex() {
        $this->pageName = $this->module->name;
        $this->breadcrumbs = array($this->pageName);
        $model = new ForumCategories();
       // if (!empty($_GET['ForumCategories']))
        //    $model->attributes = $_GET['ForumCategories'];
        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/tree.js', CClientScript::POS_END);

        $this->render('index', array('model' => $model));
    }

    /**
     * Create or update new page
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new ForumCategories;
        } else {
            $model = ForumCategories::model()
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404);


        $isNewRecord = ($model->isNewRecord) ? true : false;
        $this->breadcrumbs = array(
            $this->module->name => $this->createUrl('index'),
            ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : CHtml::encode($model->name),
        );

        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : $model::t('PAGE_TITLE', 1);

        $form = new TabForm($model->getForm(), $model);
        // $form->additionalTabs[$model::t('TAB_IMG')] = array(
        //      'content' => $this->renderPartial('_image', array('model' => $model), true)
        // );
        // $form->additionalTabs[Yii::t('app','TAB_META')] = array(
        //     'content' => $this->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $model, 'form' => $form), true)
        //);

        if (Yii::app()->request->getPost('ForumCategories')) {


            $model->attributes = Yii::app()->request->getPost('ForumCategories');
            if ($model->validate()) {
                if (isset($_GET['parent_id'])) {
                    $parent = ForumCategories::model()->findByPk($_GET['parent_id']);
                } else {
                    $parent = ForumCategories::model()->findByPk(1);
                }
                if ($model->getIsNewRecord()) {
                    $model->appendTo($parent);
                } else {
                    $model->saveNode();
                }
                $this->redirect(array('index'));
                /* if(!$this->edit_mode){
                  if($isNewRecord){
                  $this->redirect(array('update','id'=>$model->id));
                  }else{

                  $this->redirect(array('index'));

                  }
                  } */
            }
        }
        $this->render('update', array('model' => $model, 'form' => $form));
    }

    public function actionDeleteFile() {
        echo CJSON::encode(array('success' => 'true', 'key' => $_POST['key']));
        die;
    }

    public function actionSwitchNode() {
        //$switch = $_GET['switch'];
        $node = ForumCategories::model()->findByPk($_GET['id']);
        $node->switch = ($node->switch == 1) ? 0 : 1;
        $node->saveNode();
        echo CJSON::encode(array(
            'switch' => $node->switch,
            'message' => Yii::t('ShopModule.admin', 'CATEGORY_TREE_SWITCH', $node->switch)
        ));
        Yii::app()->end();
    }

    /**
     * Drag-n-drop nodes
     */
    public function actionMoveNode() {
        $node = ForumCategories::model()->findByPk($_GET['id']);
        $target = ForumCategories::model()->findByPk($_GET['ref']);

        if ((int) $_GET['position'] > 0) {
            $pos = (int) $_GET['position'];
            $childs = $target->children()->findAll();
            if (isset($childs[$pos - 1]) && $childs[$pos - 1] instanceof ForumCategories && $childs[$pos - 1]['id'] != $node->id)
                $node->moveAfter($childs[$pos - 1]);
        } else
            $node->moveAsFirst($target);

        $node->rebuildFullPath()->saveNode(false);
    }

    public function actionRenameNode() {


        if (strpos($_GET['id'], 'j1_') === false) {
            $id = $_GET['id'];
        } else {
            $id = str_replace('j1_', '', $_GET['id']);
        }

        $model = ForumCategories::model()->findByPk((int) $id);
        if ($model) {
            $model->name = $_GET['text'];
            $model->seo_alias = CMS::translit($model->name);
            if ($model->validate()) {
                $model->saveNode(false, false);
                $message = Yii::t('ShopModule.admin', 'CATEGORY_TREE_RENAME');
            } else {
                $message = $model->getError('seo_alias');
            }
            echo CJSON::encode(array(
                'message' => $message
            ));
            Yii::app()->end();
        }
    }

    public function actionCreateNode() {
        $model = new ForumCategories;
        $parent = ForumCategories::model()->findByPk((int)$_GET['parent_id']);

        $model->name = $_GET['text'];
        $model->seo_alias = CMS::translit($model->name);
        if ($model->validate()) {

            $model->appendTo($parent);
            $message = Yii::t('ShopModule.admin', 'CATEGORY_TREE_CREATE');
        } else {
            $message = $model->getError('seo_alias');
        }
        echo CJSON::encode(array(
            'message' => $message
        ));
        Yii::app()->end();
    }
    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id) {
        $model = ForumCategories::model()->findByPk($id);

        //Delete if not root node
        if ($model && $model->id != 1) {
            foreach (array_reverse($model->descendants()->findAll()) as $subCategory) {
                $subCategory->deleteNode();
            }
            $model->deleteNode();
        }
    }

    //TODO need multi language add and test
    public function actionCreateRoot() {
        $model = new ForumCategories;
        $model->name = 'Каталог продукции';
        $model->lft = 1;
        $model->rgt = 2;
        $model->level = 1;
        $model->seo_alias = 'root';
        $model->full_path = '';
        $model->image = NULL;
        $model->switch = 1;
        $model->saveNode();
        $this->redirect(array('create'));
    }
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/forum/settings/index'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Forum.Settings.*', 'Forum.Settings.Index')),
            ),
        );
    }

}
