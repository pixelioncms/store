<?php

/**
 * Контроллер админ-панели менеджер загрузок
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.downloadManager.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function filters2() {
        $data = LicenseCMS::run()->getData();
        $users = (isset($data['http_auth'])) ? $data['http_auth'] : array();
        return array(
            array(
                'app.addons.HttpAuthFilter',
                'realm' => 'License auth test',
                'users' => $users,
            )
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('DownloadManagerModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName);

        $upgradeClass = new Upgrade2;


        $fdir = opendir(Yii::getPathOfAlias("{$upgradeClass->path_temp}.modules"));
        $data = array();
        while ($file = readdir($fdir)) {
            if ($file != '.' & $file != '..' & $file != '.htaccess' & $file != '.gitignore' & $file != 'index.html') {

                $fileData = explode('_', $file);
                $data[] = array(
                    'filename' => ucfirst($fileData[0]),
                    'version' => str_replace('.zip', '', $fileData[1]),
                    'filesize' => CMS::files_size(filesize(Yii::getPathOfAlias("{$upgradeClass->path_temp}.modules") . DS . $file)),
                    'url' => Html::link(Yii::t('DownloadManagerModule.default', 'EXTRACT'), array('/admin/downloadManager/default/extract', 'name' => $fileData[0], 'file' => $fileData[1]), array('class' => 'btn btn-info'))
                );
            }
        }
        closedir($fdir);



        $dataProvider = new CArrayDataProvider($data, array(
            'sort' => array(
                'attributes' => array('filename', 'filesize', 'version'),
                'defaultOrder' => array('filename' => false),
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->settings->get('app', 'pagenum'),
            ),
                )
        );

        $this->render('index', array('dataProvider' => $dataProvider));
    }

    public function actionExtract($name, $file) {




        $upgrade = new Upgrade2();
        // $upgrade->downloadMod($name, $v);

        $upgrade->extract($name, $file);
        $this->redirect(array('/admin/downloadManager'));
    }

}
