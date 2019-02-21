<?php

/**
 * Это действие вызывается при adminList виджета для удаление записей или записи.
 *
 * Пример кода для контроллера:
 * <code>
 * public function actions() {
 *      return array(
 *          'delete' => array(
 *              'class' => 'ext.adminList.actions.DeleteAction',
 *          )
 *      );
 * }
 * </code>
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList.actions
 * @uses CAction
 */
class DeleteAction extends CAction
{

    /**
     * @var string
     */
    public $model;
    public $flag = false;

    /**
     * Запустить действие
     */
    public function run()
    {
        $json = array();
        if (isset($_REQUEST)) {
            $model = (isset($this->model)) ? call_user_func(array($this->model, 'model')) : call_user_func(array($_REQUEST['model'], 'model'));
            $entry = $model->findAllByPk($_REQUEST['id']);
            if (!empty($entry)) {
                foreach ($entry as $page) {

                    if (!in_array($page->primaryKey, $model->disallow_delete)) {
                        if ($this->flag) {
                            $page->is_deleted = 1;
                            $page->update();
                        } else {
                            $page->delete();
                        }

                        // $page->deleteByPk($_REQUEST['id']);
                        $json = array(
                            'status' => 'success',
                            'message' => Yii::t('app', 'SUCCESS_RECORD_DELETE')
                        );
                    } else {
                        $json = array(
                            'status' => 'error',
                            'message' => Yii::t('app', 'ERROR_RECORD_DELETE')
                        );
                    }
                }
            }

        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

}
