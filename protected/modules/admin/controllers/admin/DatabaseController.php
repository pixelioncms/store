<?php

class DatabaseController extends AdminController {

    public $topButtons = false;
    public $icon = 'icon-database';
    //  private $_filesizes;
    public function actionIndex() {
        $database = Yii::app()->db;
        $model = new SettingsDatabaseForm();
        $this->pageName = Yii::t('app', 'DATABASE');
        $this->breadcrumbs = array($this->pageName);
 
        if (Yii::app()->request->getPost('SettingsDatabaseForm')) {
            $model->attributes = Yii::app()->request->getPost('SettingsDatabaseForm');
            if ($model->validate()) {
                $model->save();
                if ($model->backup) {
                    $database->export();
                }
                $this->refresh();
            }
        }


        $fdir = opendir(Yii::getPathOfAlias($database->backupPath));
        $data = array();

        while ($file = readdir($fdir)) {

            if ($file != '.' & $file != '..' & $file != '.htaccess' & $file != '.gitignore' & $file != 'index.html') {
                $data[] = array(
                    
                    'filename' => $file,
                    'filesize'=>CMS::files_size(filesize(Yii::getPathOfAlias($database->backupPath).DS.$file)),
                    'url' => Html::link('<i class="icon-delete"></i>', array('/admin/app/database/delete','file'=>$file),array('class'=>'btn btn-xs btn-danger'))
                    );
            }
            //   $this->_filesizes += filesize(Yii::getPathOfAlias($database->backupPath).DS.$file);
        }
        closedir($fdir);

        $data_db = new CArrayDataProvider($data, array(
          'keyField'=>false,
            'sort' => array(
                'attributes' => array('filename','filesize'),
                'defaultOrder' => array('filename' => false),
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->settings->get('app', 'pagenum'),
            ),
                )
        );
        $this->render('index', array('model' => $model, 'data_db' => $data_db, 'database' => $database));
    }

    public function actionDelete() {
        $filedel = $_GET['file'];
        if (isset($filedel)) {
            $filePath = Yii::getPathOfAlias(Yii::app()->db->backupPath) . DS . $filedel;
            if (file_exists($filePath)) {
                @unlink($filePath);
                $this->setFlashMessage(Yii::t('app', 'FILE_SUCCESS_DELETE'));
                $this->redirect(array('admin/database'));
            } else {
                $this->setFlashMessage(Yii::t('app', 'ERR_FILE_NOT_FOUND'));
            }
        }
    }

}
