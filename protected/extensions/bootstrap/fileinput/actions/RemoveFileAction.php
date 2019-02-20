<?php

/**
 * Это действие вызывается при fileinput виджета для удаление записей или записи.
 * 
 * Пример кода для контроллера:
 * <code>
 * public function actions() {
 *      return array(
 *          'removeFile' => array(
 *              'class' => 'ext.bootstrap.fileinput.actions.RemoveFileAction',
 *          )
 *      );
 * }
 * </code>
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage fileinput.actions
 * @uses CAction
 */
class RemoveFileAction extends CAction {

    /**
     * @var string 
     */
    public $model;
    public $dir;
    public $attribute = 'filename';

    /**
     * Запустить действие
     */
    public function run() {
        $json = array();
        $attr = $this->attribute;
        if (isset($_REQUEST)) {
            if (Yii::app()->request->isPostRequest) {
                $model = (isset($this->model)) ? call_user_func(array($this->model, 'model')) : call_user_func(array($_REQUEST['model'], 'model'));
                $entry = $model->findAllByPk($_REQUEST['key']);
                if (!empty($entry)) {
                    foreach ($entry as $page) {
                        if ($page->$attr && file_exists(Yii::getPathOfAlias("webroot.uploads.{$this->dir}") . DS . $page->$attr)) {
                            //$this->setFlashMessage(Yii::t('app', 'FILE_DELETE_SUCCESS'));
                            unlink(Yii::getPathOfAlias("webroot.uploads.{$this->dir}") . DS . $page->$attr);

                            $page->$attr = NULL;
                            $page->save(false, false, false);

                        }
                        $json = array();
                    }
                    echo CJSON::encode($json);
                    Yii::app()->end();
                }
            }
        }
    }

}
