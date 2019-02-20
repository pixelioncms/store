<?php

//namespace application\modules\seo\DefaultController;

class DefaultController extends AdminController
{

    public function allowedActions()
    {
        return 'addmetaproperty, deletemetaproperty, deletemetaname, addmetaname';
    }

    public function actions()
    {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionCreate()
    {

        $model = new SeoUrl;
        $this->pageName = Yii::t('app', 'CREATE', 1);
        if (isset($_POST['SeoUrl'])) {
            $model->attributes = $_POST['SeoUrl'];
            if($model->validate()){
                if ($model->save()) {
                    /* save MetaName */
                    if (isset($_POST['SeoMain'])) {
                        $items = $_POST['SeoMain'];
                        foreach ($items as $name => $item) {
                            $mod = new SeoMain();
                            $mod->name = $name;
                            $mod->url = $model->id;
                            $mod->attributes = $item;
                            $mod->save();
                        }
                    }

                    $this->redirect(array("index"));
                }
            }else{
                print_r($model->getErrors());die;
            }

        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->pageName = Yii::t('app', 'UPDATE', 0);
        if (isset($_POST['SeoUrl'])) {
            $model->attributes = $_POST['SeoUrl'];
            /* update url */
            if ($model->save()) {

                /* save or update MetaName */
                if (isset($_POST['SeoMain'])) {

                    $items = $_POST['SeoMain'];
                    foreach ($items as $name => $item) {

                        if (isset($item['id'])) {
                            $mod = SeoMain::model()->findByPk($item['id']);
                        } else {

                            $mod = new SeoMain();
                            $mod->name = $name;
                            $mod->url = $model->id;
                        }

                        $mod->attributes = $item;
                        $mod->save(false, false);
                    }
                }

                $this->saveParams($model);


                $this->redirect(array("index"));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    protected function saveParams($model)
    {
        $dontDelete = array();

        if (!empty($_POST['param'])) {
            foreach ($_POST['param'] as $main_id => $object) {
                // echo '<pre>'.CVarDumper::dumpAsString($object).'</pre>';


                $i = 0;
                foreach ($object as $key => $item) {
                    $variant = SeoParams::model()->findByAttributes(array(
                        'url_id' => $main_id,
                        'param' => $item,
                        'obj' => $key
                    ));
                    // If not - create new.
                    if (!$variant)
                        $variant = new SeoParams();

                    $variant->setAttributes(array(
                        'url_id' => $main_id,
                        'param' => $item,
                        'obj' => $key,
                    ), false);

                    $variant->save(false, false, false);
                    array_push($dontDelete, $variant->id);
                    $i++;
                }


                if (!empty($dontDelete)) {
                    $cr = new CDbCriteria;
                    $cr->addNotInCondition('id', $dontDelete);
                    $cr->addCondition('url_id=' . $main_id);
                    SeoParams::model()->deleteAll($cr);
                } else
                    SeoParams::model()->deleteAllByAttributes(array('url_id' => $main_id));
            }
        }
        //   die;
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {

        $model = new SeoUrl('search');
        $model->unsetAttributes();
        $this->pageName = Yii::t('SeoModule.default', 'MODULE_NAME');
        if (isset($_GET['SeoUrl'])) {
            $model->attributes = $_GET['SeoUrl'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return SeoUrl
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        /*$model = SeoUrl::model()->findByAttributes(array(
            'id' => $id,
            //'domain' => SeoUrl::getDomainId()
        ));*/

        //uncomment for only one domain.
        $model = SeoUrl::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Получение списка всех моделей в проекте
     */
    public function getModels()
    {
        $file_list = array();
        //путь к директории с проектами
        //$file_list = scandir(Yii::getPathOfAlias('application.modules.news.models'));
        //$file_list = scandir(Yii::getPathOfAlias('mod.*.models'));
        $models = null;

        foreach (Yii::app()->getModules() as $mod => $obj) {
            //echo $mod;
            if (!in_array($mod, array('admin', 'rights', 'seo', 'users', 'install', 'stats', 'license'))) {
                if (file_exists(Yii::getPathOfAlias("mod.{$mod}.models"))) {
                    $file_list[$mod] = scandir(Yii::getPathOfAlias("mod.{$mod}.models"));
                }
            }


            //если найдены файлы
            if (isset($file_list[$mod])) {
                if (count($file_list[$mod])) {
                    foreach ($file_list[$mod] as $file) {
                        if ($file != '.' && $file != '..' && !preg_match('/Translate/', $file)) {// исключаем папки с назварием '.' и '..'
                            // Yii::import("mod.{$mod}.models.{$file}");
                            $ext = explode(".", $file);
                            $model = $ext[0];
                            //if (new $model instanceof ActiveRecord) {
                            //проверяем чтобы модели были с расширением php
                            if (isset($ext[1])) {
                                if ($ext[1] == "php") {
                                    $models[] = array(
                                        'model' => $model,
                                        'path' => "mod.{$mod}.models"
                                    );
                                    //  $models[] = "mod.{$mod}.models";
                                }
                            }
                            // }
                        }
                    }
                }
            }
        }
        return $models;
    }

    /**
     * Получение списка артибутов всех моделей
     */
    public function getParams()
    {
        //загружаем модели
        $models = $this->getModels();
        $params = array();
        $i = 0;
        $preg_pattern_disallow = '/[a-zA-Z]_id\b/';

        if (count($models)) {
            foreach ($models as $model) {
                $modelClasss = $model['model'];


                if ($modelClasss != 'ShopCategoryNode' || $modelClasss != 'ShopCategoryNodeNew' || $modelClasss != 'SubscribeForm') {
                    Yii::import("{$model['path']}.{$modelClasss}");
                    $mdl = new $modelClasss(null);


                    if ($mdl instanceof ActiveRecord || $mdl instanceof CActiveRecord) {
                        //if($mdl!='ShopCategoryNode'){
                        // }
                        /* проверяем существует ли в данном классе функция "tableName"
                         * если она существует, то скорее всего эта модель CActiveRecord
                         * таким образом отсеиваем модели, которые были предназначены для валидации форм не работающих с Базой Данных
                         */

                        //if($mdl!='ShopCategoryNode'){

                        if (method_exists($mdl, "tableName")) {

                            $tableName = $mdl->tableName();

                            if (($table = $mdl->getDbConnection()->getSchema()->getTable($tableName)) !== null) {

                                //  $item = new $mdl;

                                $behaviors = $mdl->behaviors();
                                if (array_key_exists('seo', $behaviors)) {
                                    $name = $mdl::MODULE_ID;
                                    $fname = ucfirst($name);
                                    Yii::import("mod.{$name}.{$fname}Module");
                                    foreach ($mdl as $attr => $val) {
                                        if (!preg_match($preg_pattern_disallow, $attr)) {
                                            //$params[$i]['group'] = Yii::t(ucfirst($name).'Module.'.$modelClasss,'MODEL_NAME');
                                            $params[$i]['group'] = Yii::t($fname . 'Module.default', 'MODULE_NAME') . ' ' . $modelClasss;
                                            $params[$i]['name'] = $attr;
                                            $params[$i++]['value'] = $modelClasss . '/' . $attr;
                                        }
                                    }

                                    //проверяем есть ли связи у данной модели
                                    if (method_exists($mdl, "relations")) {
                                        //if (count($mdl->relations())) {
                                        $relation = $mdl->relations();
                                        foreach ($relation as $key => $rel) {
                                            // выбираем связи один к одному или многие к одному
                                            if (($rel[0] == "CHasOneRelation") || ($rel[0] == "CBelongsToRelation")) {

                                                if (!in_array($rel[1], array('CategoriesModel', 'AttachmentModel'))) {

                                                    //
                                                    // echo $model['path'];
                                                    /* $cModel = $rel[1];
                                                     $ss = explode('.', $cModel);
                                                     if (count($ss) > 1) {
                                                         Yii::import($rel[1]);
                                                         $cModel = end($ss);
                                                     } else {
                                                         Yii::import($rel[1]);
                                                     }

                                                     $newRel = new $cModel;*/
                                         
                                                    if (file_exists(Yii::getPathOfAlias("{$model['path']}.{$rel[1]}"))) {
                                                        Yii::import("{$model['path']}.{$rel[1]}");
                                                        $newRel = new $rel[1];
                                                        foreach ($newRel as $attr => $nR) {
                                                            if (!preg_match($preg_pattern_disallow, $attr)) {
                                                                $params[$i]['group'] = Yii::t(ucfirst($name) . 'Module.default', 'MODULE_NAME') . ' ' . $modelClasss;
                                                                $params[$i]['name'] = $key . "." . $attr;
                                                                $params[$i++]['value'] = $modelClasss . "/" . $key . "." . $attr;
                                                            }
                                                        }
                                                    }

                                                }
                                            }
                                        }

                                        //}
                                    }

                                }
                            }
                        }
                    }
                }
            }
            /*
             * если есть модели работающие с базой то возвращаем массив данных
             * иначе возвращаем пустой массив
             */
        }

        return $params;
    }

    /*
     * ajax function
     * add to Form, fields for MetaName
     */

    public function actionAddmetaname()
    {
        $model = new SeoMain;
        $model->name = $_POST['name'];
        $this->renderPartial("_formMetaName", array('model' => $model));
    }

    /*
     * ajax function
     * delete MetaName
     */

    public function actionDeletemetaname()
    {

        if ($model = SeoMain::model()->findByPk($_POST['id'])) {
            $model->delete();
        }
        Yii::app()->end();

    }

    /*
     * ajax function
     * add to Form, fields for MetaProperty
     */

    public function actionAddmetaproperty()
    {
        $model = new SeoParams();
        $this->renderPartial("_formMetaParams", array('model' => $model, 'count' => $_POST['count']));
    }

    /*
     * ajax function
     * delete MetaProperty
     */

    public function actionDeletemetaproperty()
    {
        SeoParams::model()->findByPk($_POST['id'])->delete();
    }

    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/seo/settings'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Seo.Settings.*', 'Seo.Settings.Index')),
            ),
            array(
                'label' => Yii::t('SeoModule.default', 'REDIRECTS'),
                'url' => array('/admin/seo/redirects'),
                'icon' => Html::icon('icon-refresh'),
                'visible' => Yii::app()->user->openAccess(array('Seo.Redirects.*', 'Seo.Redirects.Index')),
            ),
        );
    }

}
