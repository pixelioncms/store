<?php

class DesktopController extends AdminController {

    public $topButtons = false;
    
    public function actions() {
        return array(
            'sortable' => array(
                'class' => 'ext.sortable.SortableDesktopAction',
                'model' => DesktopWidgets::model(),
            )
        );
    }
    
    public function actionUpdate($new = false) {
        $model = ($new === true) ? new Desktop : Desktop::model()->findByPk($_GET['id']);
        $model->accessControlDesktop();
        $pageName = ($new === true) ? Yii::t('app', 'CREATE', 1) : $model->name;
        $this->pageName = $pageName;
        $this->breadcrumbs = array($this->pageName);

        if (isset($model)) {
            if (isset($_POST['Desktop'])) {
                $model->attributes = $_POST['Desktop'];

                if ($model->validate()) {
                    $model->save();
                    $this->redirect('/admin?d=' . $model->id);
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionCreateWidget($id) {

        $model = new DesktopWidgets;

        if (isset($_POST['DesktopWidgets'])) {
            $model->desktop_id = $id;
            $model->attributes = $_POST['DesktopWidgets'];
            if ($model->validate()) {
                $model->save();
                Yii::app()->cache->flush();
            }else{
                print_r($model->getErrors());die;
            }
        }

        Yii::app()->getClientScript()->scriptMap = array(
            'jquery.js' => false,
            'jquery.min.js' => false,
        );
        $this->render('widget_create', array('model' => $model), false, true);
    }

    /**
     * Delete widget
     * @param $id
     */
    public function actionDeleteWidget($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = DesktopWidgets::model()->findByPk($id);
            //$model->desktop->accessControlDesktop();
            if (isset($model)) {
                $model->delete();
            }
            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('admin');
        }
    }

    /**
     * Delete dekstop
     * @param int $id
     */
    public function actionDelete($id) {
        $model = Desktop::model()->findByPk($id);
        $model->accessControlDesktop();
        if (isset($model) && $model->id != 1) {
            $model->delete();
            unset(Yii::app()->session['desktop_id']);
        }
        if (!Yii::app()->request->isAjaxRequest)
            $this->redirect(array('/admin'));
    }

}
