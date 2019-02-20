<?php

/**
 * Manage system languages
 * @package app.systemLanguages
 */
class LanguagesController extends AdminController
{

    public $icon = 'icon-language';

    public function allowedActions()
    {
        return 'ajaxOnlineTranslate';
    }

    public function actions()
    {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex()
    {
        $this->pageName = Yii::t('app', 'LANGUAGES');
        $this->breadcrumbs = array($this->pageName);

        $this->topButtons = array(array(
            'label' => Yii::t('admin', 'CREATE_LANG'),
            'url' => Yii::app()->createUrl('admin/app/languages/create'),
            'htmlOptions' => array('class' => 'btn btn-success')
        ));
        $model = new LanguageModel('search');

        if (isset($_GET['LanguageModel'])) {
            $model->attributes = $_GET['LanguageModel'];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($new = false)
    {

        $model = ($new === true) ? new LanguageModel : LanguageModel::model()->findByPk($_GET['id']);
        $this->breadcrumbs = array(
            Yii::t('app', 'LANGUAGES') => $this->createUrl('index'),
            ($model->isNewRecord) ? Yii::t('admin', 'CREATED_LANG', 0) : CHtml::encode($model->name),
        );
        $this->pageName = ($model->isNewRecord) ? Yii::t('admin', 'CREATED_LANG', 0) : Yii::t('admin', 'CREATED_LANG', 1);
        if (!$model)
            throw new CHttpException(404, Yii::t('admin', 'LANG_NOFIND'));

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['LanguageModel'];
            if ($model->validate()) {
                $model->save();
                if ($model->start_auto_translate) {
                    $this->redirect(array('/admin/app/translates/application', 'lang' => $model->code));
                } else {
                    $this->redirect(array('index'));
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionAjaxOnlineTranslate()
    {
        $this->onlineTranslate($_POST['lang'], $_POST['text']);
    }

    public function onlineTranslate($lang = array(), $text)
    {
        $t = new yandexTranslate;
        $response = $t->translate(array($lang[0], $lang[1]), $text);
        header('Content-Type: application/json');
        echo CJSON::encode($response['text']);
    }

    public function actionOnline()
    {
        $this->pageName = 'Онлайн переводчик';
        $this->render('online');
    }


    private function writeContent($filePath, $content)
    {
        if (!@file_put_contents($filePath, '<?php

/**
 * Message translations.
 * 
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of \'@@\' marks.
 * 
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link https://pixelion.com.ua PIXELION CMS
 * @ignore
 */
return ' . var_export($content, true) . ';')
        ) {
            throw new CException(Yii::t('app', 'Error write modules setting in {file}...', array('{file}' => $filePath)));
        }
    }







    public function actionEditorLang()
    {
        $json = array();
        $lang = Yii::app()->request->getPost('lang');
        $key = Yii::app()->request->getPost('key');
        $category = Yii::app()->request->getPost('category');
        $file = Yii::app()->request->getPost('file');

        //$file=CMS::getTranslateFile($category);


//die($file);
            if (file_exists($file)) {
                //Load file array
                $list = require_once($file);
                //find var
                if (array_key_exists($key, $list)) {
                    //Если ключ найден, то редактируем
                    if (isset($lang)) {
                        $list[$key] = $lang[$category][$key]['value'];
                        $json['message'] = 'Переменая успешно отредактирована';
                    }
                } else {
                    //Если ключ НЕ найден, то добавляем
                    $fullList = $list;
                    if (isset($lang)) {
                        $params[$key] = $lang[$category][$key]['value'];
                        $list = CMap::mergeArray($params, $fullList);
                        $json['message'] = 'Новая переменая добавлена в языки.';
                    }
                }
                $this->writeContent($file, $list);
            }else{
                //Если файл не найден, то создаем.
                //Todo panix
                if(!file_exists($file)){

                }

                $json['message'] = 'file no found: '.$file;
            }

        $json['status'] = 'success';
        echo CJSON::encode($json);
        Yii::app()->end();
    }


    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('admin', 'Онлайн переводчик'),
                'url' => array('/admin/app/languages/online'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Languages.*', 'Admin.Languages.Online'))
            ),
            array(
                'label' => Yii::t('app', 'TRANSLATES'),
                'url' => array('/admin/app/translates'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Translates.*', 'Admin.Translates.Index'))
            ),
            array(
                'label' => Yii::t('app', 'Перевод всего сайта'),
                'url' => array('/admin/app/translates/application'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Translates.*', 'Admin.Translates.Application'))
            ),
        );
    }

}
