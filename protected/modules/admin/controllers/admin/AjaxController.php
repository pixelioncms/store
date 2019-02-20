<?php

class AjaxController extends AdminController
{

    public function actions()
    {
        return array(
            'widget.' => 'ext.adminList.EditGridColumnsWidget',
            'ap.' => 'ext.admin.sitePanel.PanelWidget',
            'attachment.' => 'ext.attachment.AttachmentWidget',
            'attachmentSortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => AttachmentModel::model()
            )
        );
    }

    public function allowedActions()
    {
        return 'geo, counters, updateGridRow, deleteFile, getStats, checkalias, autocomplete, sendMailForm';
    }

    public function actionSetHashstate()
    {
        Yii::app()->user->setState('redirectTabsHash', $_POST['hash']);
    }

    public function actionGeo($ip)
    {
        $this->render('_geo', array(
            'ip' => $ip
        ));
    }

    public function actionNotifications()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $ids = Yii::app()->request->getPost('ids');
            if ($ids) {
                Yii::app()->db->createCommand("UPDATE " . NotificationModel::model()->tableName() . " SET status = '0' WHERE id IN(" . implode(', ', $ids) . ")")->execute();
            }
            $reuslt = array(
                'status' => 'success',
                'items' => $ids
            );
            echo CJSON::encode($reuslt);
            Yii::app()->end();
        }
    }

    public function actionCounters()
    {
        Yii::import('mod.cart.models.Order');
        echo CJSON::encode(array(
            // 'comments' => (int) Comment::model()->waiting()->count(),
            //'orders' => Yii::app()->getModule('cart')->countOrder,
        ));
    }

    /**
     * Экшен для CEditableColumn
     * @throws CHttpException
     */
    public function actionUpdateGridRow()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $response = array();
            $id = (int)Yii::app()->request->getPost('pk');
            $attribute = Yii::app()->request->getPost('attribute');
            $modelAlias = Yii::app()->request->getPost('modelAlias');
            \
            Yii::import($modelAlias);
            $modelAliasFragments = explode('.', $modelAlias);
            $modelClass = end($modelAliasFragments);

            $q = Yii::app()->request->getPost('q');
            $model = $modelClass::model()->findByPk($id);
            $model->{$attribute} = $q;
            // $model->attributes = array($attribute=>$q);
            /*if ($model->validate(false)) {*/
            $model->save(false, false, false);
            $response['message'] = Yii::t('app', 'SUCCESS_UPDATE');
            $response['value'] = $model->{$attribute};
            $response['success'] = true;
            /* } else {
                 $response['success'] = false;
                 $response['message'] = 'error validate'.Yii::t('app', 'ERROR_UPDATE');
                 foreach($model->getErrors() as $error){
                     $response['error'][] = $error;
                 }
             }*/
            echo CJSON::encode($response);
            Yii::app()->end();
        } else {
            throw new CHttpException(Yii::t('error', '403'));
        }
    }

    public function actionDeleteFile()
    {
        $dir = $_POST['aliasDir'];
        $filename = $_POST['filename'];
        $model = $_POST['modelClass'];
        $record_id = $_POST['id'];
        $attr = $_POST['attribute'];
        $path = Yii::getPathOfAlias($dir);
        if (file_exists($path . DIRECTORY_SEPARATOR . $filename)) {
            unlink($path . DIRECTORY_SEPARATOR . $filename);
            $m = $model::model()->findByPk($record_id);
            $m->$attr = '';
            $m->save(false, false, false);
            echo CJSON::encode(array(
                    'response' => 'success',
                    'message' => Yii::t('app', 'FILE_SUCCESS_DELETE')
                )
            );
        } else {
            echo CJSON::encode(array(
                    'response' => 'error',
                    'message' => Yii::t('app', 'ERR_FILE_NOT_FOUND')
                )
            );
        }
    }

    public function actionCheckalias()
    {
        $model = Yii::app()->request->getPost('model_path');
        $value = Yii::app()->request->getPost('value');
        $isNew = Yii::app()->request->getPost('isNew');

        Yii::import($model);
        $exp = explode('.', $model);
        $modelClass = end($exp);


        $criteria = new CDbCriteria();
        if (!empty($isNew)) {
            $criteria->condition = '`t`.`seo_alias`="' . $value . '" AND `t`.`id`!=' . $isNew;
        } else {
            $criteria->condition = '`t`.`seo_alias`="' . $value . '"';
        }
        $check = $modelClass::model()->find($criteria);

        if (isset($check))
            echo CJSON::encode(array('result' => true));
        else
            echo CJSON::encode(array('result' => false));
        die;
    }

    public function actionGetStats()
    {
        $n = Stats::model()->findAll();
        echo CJSON::encode(array(
            'hits' => (int)count($n),
            'hosts' => (int)count($n),
        ));
    }

    public function actionAutocomplete()
    {
        $model = $_GET['modelClass'];
        $string = $_GET['string'];
        $field = $_GET['field'];
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.' . $field, $string);
        $results = $model::model()->findAll($criteria);

        $json = array();
        foreach ($results as $item) {
            $json[] = array(
                'label' => $item->title,
                'value' => $item->title,
                'test' => 'test.param'
            );
        }
        echo CJSON::encode($json);
    }

    public function actionSendMailForm()
    {
        Yii::import('mod.admin.models.MailForm');
        $model = new MailForm;
        $model->toemail = $_GET['mail'];
        $form = new CMSForm($model->config, $model);
        $this->renderPartial('_sendMailForm', array('form' => $form));
    }

}
