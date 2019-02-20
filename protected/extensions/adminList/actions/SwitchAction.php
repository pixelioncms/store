<?php

/**
 * Это действие вызывается при adminList виджета для скрыть/показать записи или запись.
 *
 * Пример кода для контроллера:
 * <code>
 * public function actions() {
 *      return array(
 *          'switch' => array(
 *              'class' => 'ext.adminList.actions.SwitchAction',
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
 *
 */
class SwitchAction extends CAction
{

    /**
     * Запустить действие
     */
    public function run()
    {
        $json = array();
        if (isset($_REQUEST)) {
            if (Yii::app()->request->isPostRequest) {
                $model = call_user_func(array($_REQUEST['model'], 'model'));
                $entry = $model->findAllByPk($_REQUEST['id']);
                if (!empty($entry)) {
                    foreach ($entry as $page) {
                        $json['list'][] = $_REQUEST['id'];
                        $page->updateByPk($_REQUEST['id'], array('switch' => $_REQUEST['switch']));
                    }
                    Yii::app()->timeline->set('SWITCH_RECORD', array(
                        '{model}' => $_REQUEST['model'],
                        '{pk}' => $_REQUEST['id'],
                        '{switch}' => ($_REQUEST['switch']) ? Yii::t('app', 'ON', 0) : Yii::t('app', 'OFF', 0),
                        '{htmlClass}' => ($_REQUEST['switch']) ? 'success' : 'danger',
                    ));
                }
                if ($model instanceof ShopProduct) {
                    ShopProductCategoryRef::model()->updateAll(array(
                        'switch' => $_REQUEST['switch']
                    ), 'product=:p', array(':p' => $_REQUEST['id']));
                }
                $sw = ($_REQUEST['switch'])?0:1;
                $json['success'] = true;
                $json['value'] = $sw;

                $json['url'] = '/'.Yii::app()->request->pathInfo."?model={$_REQUEST['model']}&id={$_REQUEST['id']}&switch={$sw}";

            }
        }
        echo CJSON::encode($json);
        Yii::app()->end();
    }

}
