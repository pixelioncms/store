<?php

class AttributeController extends AdminController
{

    public $icon = 'icon-filter';

    public function actions()
    {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ShopAttribute::model(),
            ),
        );
    }

    public function actionIndex()
    {
        $model = new ShopAttribute('search');

        if (!empty($_GET['ShopAttribute']))
            $model->attributes = $_GET['ShopAttribute'];

        $this->pageName = Yii::t('ShopModule.admin', 'ATTRIBUTES');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update attribute
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false)
    {
        $this->topButtons = false;
        if ($new === true)
            $model = new ShopAttribute;
        else {
            $model = ShopAttribute::model()
                ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_ATTR'));


        $this->pageName = ($model->isNewRecord) ? $model::t('ISNEW', 0) : $model::t('ISNEW', 1);

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'ATTRIBUTES') => $this->createUrl('index'),
            $this->pageName
        );

        $form = $model->getForm();
        $form->additionalTabs = array(
            Yii::t('app', 'OPTIONS') => $this->renderPartial('_options', array(
                'model' => $model,
            ), true),
        );
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopAttribute'];
            if ($model->validate()) {
                $model->save();
                $this->saveOptions($model);
                if ($new) {
                    $this->redirect(array('index'));
                } else {
                    $this->redirect(array('update', 'id' => $_GET['id']));
                }
            }
        }

        $this->render('update', array('model' => $model, 'form' => $form));
    }

    /**
     * Save attribute options
     * @param ShopAttribute $model
     */
    protected function saveOptions($model)
    {
        //  print_r(Yii::app()->languageManager->languages);
        //    die;
        $dontDelete = array();
        if (!empty($_POST['options'])) {
            foreach ($_POST['options'] as $key => $val) {
                if (isset($val[0]) && $val[0] != '') {
                    $index = 0;

                    $attributeOption = ShopAttributeOption::model()
                        ->findByAttributes(array(
                            'id' => $key,
                            'attribute_id' => $model->id,
                        ));


                    if (!$attributeOption) {
                        $attributeOption = new ShopAttributeOption;

                        $attributeOption->attribute_id = $model->id;
                        //  $attributeOption->date_create = da;
                    }
                    $attributeOption->spec = $val['spec'];
                    $attributeOption->save(false, false, false);

                    foreach (Yii::app()->languageManager->languages as $lang) {
                        $attributeLangOption = ShopAttributeOption::model()
                            ->language($lang->id)
                            ->findByAttributes(array('id' => $attributeOption->id));
                        $attributeLangOption->value = $val[$index];

                        $attributeLangOption->save(false, false, false);
                        ++$index;
                    }
                    array_push($dontDelete, $attributeOption->id);
                }
            }
        }

        if (sizeof($dontDelete)) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('t.id', $dontDelete);
            $optionsToDelete = ShopAttributeOption::model()->findAllByAttributes(array(
                'attribute_id' => $model->id
            ), $cr);
        } else {
            // Clear all attribute options
            $optionsToDelete = ShopAttributeOption::model()->findAllByAttributes(array(
                'attribute_id' => $model->id
            ));
        }

        if (!empty($optionsToDelete)) {
            foreach ($optionsToDelete as $o)
                $o->delete();
        }
    }

    /**
     * Delete attribute
     * @param array $id
     * @throws CHttpException
     */
    public function actionDelete($id = array())
    {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopAttribute::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    $count = ShopProduct::model()->withEavAttributes(array($m->name))->count();
                    if ($count)
                        throw new CHttpException(503, Yii::t('ShopModule.admin', 'ERR_DEL_ATTR'));
                    $m->delete();
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('ShopModule.admin', 'ATTRIBUTES_GROUP'),
                'url' => array('/admin/shop/attributeGroups'),
                'visible' => true
            ),
        );
    }

}
