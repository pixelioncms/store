<?php

class WidgetsController extends AdminController {

    public $icon = 'icon-chip';

    const CHACHEID = 'widgets_cache';

    public function actionIndex() {

        $this->pageName = Yii::t('app', 'WIDGETS');
        $this->breadcrumbs = array($this->pageName);


        $result = Yii::app()->cache->get(self::CHACHEID);
        if ($result === false) {
            $result = array();

            $extPath = Yii::getPathOfAlias("ext.blocks");
            $files = CFileHelper::findFiles($extPath, array(
                        'fileTypes' => array('php'),
                        'level' => 1,
                        'absolutePaths' => false,
            ));
            Yii::import('app.blocks_settings.*');
            $manager = new WidgetSystemManager;
            foreach ($files as $k => $obj) {

                $obj = explode(DIRECTORY_SEPARATOR, $obj);


                $className = str_replace('.php', '', $obj[1]);
                $classDir = $obj[0];

                if (file_exists(Yii::getPathOfAlias("ext.blocks.{$classDir}") . DS . $obj[1])) {
                    Yii::import("ext.blocks.{$classDir}.{$className}");
                    if (new $className instanceof BlockWidget) {
                        $class = new $className;

                        Yii::import('app.blocks_settings.*');
                        $manager = new WidgetSystemManager;

                        $system = $manager->getClass("ext.blocks.{$classDir}", $className);

                        if (!$system) {
                            $edit = false;
                        } else {
                            $edit = true;
                        }


                        $result[] = array(
                            'title' => $class->getTitle(),
                            'alias' => "ext.blocks.{$classDir}.{$className}",
                            'category' => 'ext',
                            'edit' => ($edit) ? Html::link('<i class="icon-edit"></i>', array('/admin/app/widgets/update', 'alias' => "ext.blocks.{$classDir}.{$className}"), array('class' => 'btn btn-secondary')) : ''
                        );
                    }
                }
            }




            /* start modules widget parse */
            foreach (Yii::app()->getModules() as $mod => $module) {
                if (file_exists(Yii::getPathOfAlias("mod.{$mod}.blocks"))) {
                    $modulesfile = CFileHelper::findFiles(Yii::getPathOfAlias("mod.{$mod}.blocks"), array(
                                'fileTypes' => array('php'),
                                'level' => 1,
                                'absolutePaths' => false
                    ));
                }
                foreach ($modulesfile as $obj) {

                    $obj = explode(DIRECTORY_SEPARATOR, $obj);


                    $className = str_replace('.php', '', $obj[1]);
                    $classDir = $obj[0];
                    if (file_exists(Yii::getPathOfAlias("mod.{$mod}.blocks"))) {
                        if (file_exists(Yii::getPathOfAlias("mod.{$mod}.blocks.{$classDir}") . DS . $obj[1])) {
                            Yii::import("mod.{$mod}.blocks.{$classDir}.{$className}");
                            if (new $className instanceof BlockWidget) {
                                $class = new $className;



                                $system = $manager->getClass("mod.{$mod}.blocks.{$classDir}", $className);

                                if (!$system) {
                                    $edit = false;
                                } else {
                                    $edit = true;
                                }


                                $result[] = array(
                                    'title' => $class->getTitle(),
                                    'alias' => "mod.{$mod}.blocks.{$classDir}.{$className}",
                                    'category' => 'module',
                                    'edit' => ($edit) ? Html::link('<i class="icon-edit"></i>', array('/admin/app/widgets/update', 'alias' => "mod.{$mod}.blocks.{$classDir}.{$className}"), array('class' => 'btn btn-secondary')) : ''
                                );
                            }
                        }
                    }
                }
            }
            Yii::app()->cache->set(self::CHACHEID, $result, 3600 * 12);
        }
        $data_db = new CArrayDataProvider($result, array(
            'keyField' => false,
            'sort' => array(
                'attributes' => array('alias', 'category', 'title'),
                'defaultOrder' => array('alias' => false),
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->settings->get('app', 'pagenum'),
            ),
                )
        );
        $this->render('index', array('data_db' => $data_db));





        // print_r($result);
        die;
    }

    public function actionUpdate($alias) {
        $this->pageName = Yii::t('app', 'WIDGETS');
        $this->breadcrumbs = array(
            $this->pageName => array('/admin/app/widgets'),
            Yii::t('app', 'UPDATE', 1)
        );
        $this->topButtons = false;
        Yii::import('app.blocks_settings.*');

        if (empty($alias)) {
            $this->redirect(array('index'));
        }
        $manager = new WidgetSystemManager;
        $system = $manager->getSystemClass($alias);

        if (!$system) {
            Yii::app()->user->setFlash('error', 'Виджет не использует конфигурации');
            $this->redirect(array('index'));
        }


        if (Yii::app()->request->isPostRequest) {
            if ($system) {
                $system->attributes = $_POST[get_class($system)];
                if ($system->validate()) {
                    $system->saveSettings($alias, $_POST);
                    Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_UPDATE'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'ERROR_UPDATE'));
                }
            }
        }

        $this->render('update', array(
            'form' => $system->getConfigurationFormHtml($alias),
                //  'title'=>Yii::t(str_replace('Form','',get_class($system)).'.default','TITLE')
        ));
    }

}
