<?php

/**
 * Admin product category controller
 */
class CategoryController extends AdminController
{
    public $icon = 'icon-folder-open';


/*

    public function allowedActions()
    {
        return 'redirect';
    }
*/
    public function actionIndex()
    {
        $this->pageName = Yii::t('ShopModule.admin', 'CATEGORIES');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        $this->actionUpdate(true);
    }

    public function actionUpdate($new = false)
    {
        if ($new === true)
            $model = new ShopCategory;
        else {
            $model = ShopCategory::model()
                ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CATEGORY'));

        if (!$model->isNewRecord) {
            $this->topButtons = array(array(
                'label' => Yii::t('ShopModule.admin', 'VIEW_CATEGORY'),
                'url' => $model->getUrl(),
                'htmlOptions' => array('class' => 'btn btn-primary', 'target' => '_blank'),
            ));
        }

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopCategory'];

            if ($model->validate()) {
                if (isset($_GET['parent_id'])) {
                    $parent = ShopCategory::model()->findByPk($_GET['parent_id']);
                } else {
                    $parent = ShopCategory::model()->findByPk(1);
                }
                if ($model->getIsNewRecord()) {
                    $model->appendTo($parent);
                    $this->redirect(array('create'));
                } else {
                    $model->saveNode();
                }
            }
        }
        $title = ($model->isNewRecord) ? Yii::t('ShopModule.admin', 'Создание категории') :
            Yii::t('ShopModule.admin', 'Редактирование категории');

        $this->pageName = $title;

        $form = new TabForm($model->getForm(), $model);

        $form->additionalTabs[Yii::t('app', 'TAB_META')] = array(
            'content' => $this->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $model, 'form' => $form), true)
        );

        $this->render('update', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    public function actionRenameNode()
    {


        if (strpos($_GET['id'], 'j1_') === false) {
            $id = $_GET['id'];
        } else {
            $id = str_replace('j1_', '', $_GET['id']);
        }

        $model = ShopCategory::model()->findByPk((int)$id);
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

    public function actionCreateNode()
    {
        $model = new ShopCategory;
        $parent = ShopCategory::model()->findByPk($_GET['parent_id']);

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
     * Drag-n-drop nodes
     */
    public function actionMoveNode()
    {
        $node = ShopCategory::model()->findByPk($_GET['id']);
        $target = ShopCategory::model()->findByPk($_GET['ref']);

        if ((int)$_GET['position'] > 0) {
            $pos = (int)$_GET['position'];
            $childs = $target->children()->findAll();
            if (isset($childs[$pos - 1]) && $childs[$pos - 1] instanceof ShopCategory && $childs[$pos - 1]['id'] != $node->id)
                $node->moveAfter($childs[$pos - 1]);
        } else
            $node->moveAsFirst($target);

        $node->rebuildFullPath()->saveNode(false);
    }

    /**
     * Redirect to category front.
     */
    public function actionRedirect()
    {
        $node = ShopCategory::model()->findByPk($_GET['id']);
        $this->redirect($node->getViewUrl());
    }

    public function actionSwitchNode()
    {
        //$switch = $_GET['switch'];
        $node = ShopCategory::model()->findByPk($_GET['id']);
        $node->switch = ($node->switch == 1) ? 0 : 1;
        $node->saveNode();
        echo CJSON::encode(array(
            'switch' => $node->switch,
            'message' => Yii::t('ShopModule.admin', 'CATEGORY_TREE_SWITCH', $node->switch)
        ));
        Yii::app()->end();
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $model = ShopCategory::model()->findByPk($id);

        //Delete if not root node
        if ($model && $model->id != 1) {
            foreach (array_reverse($model->descendants()->findAll()) as $subCategory) {
                $subCategory->deleteNode();
            }
            $model->deleteNode();
        }
    }

    //TODO need multi language add and test
    public function actionCreateRoot()
    {
        $model = new ShopCategory;
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

}
