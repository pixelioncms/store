<?php
/**
 * CManagerTemplate
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */

class CManagerTemplate {

    public function init() {
        
    }

    public function openWidget($var = array()) {
        $this->render('_openWidget', $var);
    }

    public function closeWidget($var = array()) {
        $this->render('_closeWidget', $var);
    }

    public function alert($type, $text,$close=true,$htmlClass='') {
        $id = 'alert' . md5($type . CMS::translit($text));
        if (!isset($_COOKIE[$id])) {
            $types = array('info', 'warning', 'success', 'failure', 'danger', 'error');
            $str = implode(', ', $types);
            if (in_array($type, $types)) {
                $this->render('_' . __FUNCTION__, array(
                    'type' => $type,
                    'message' => $text,
                    'close'=>$close,
                    'htmlClass'=>$htmlClass
                ));
            } else {
                Yii::app()->controller->flashMessage('warning', Yii::t('app', 'TPL_' . strtoupper(__FUNCTION__), array(
                            '{tpl}' => __FUNCTION__,
                            '{type}' => $type,
                            '{types}' => CHtml::encode($str)
                                )
                        ));
            }
        }
    }

    private function render($tpl, $array = array()) {

        $module = Yii::app()->controller->module->id;
        $controller = Yii::app()->controller->id;
        if ($module == 'install') {
            $theme = 'default';
        } else {
            $theme = Yii::app()->theme->getName();
        }
        if (Yii::app()->controller instanceof AdminController) {
            $layouts = array(
                "mod.{$module}.views.layouts." . $tpl . "_{$controller}",
                "mod.{$module}.views.layouts." . $tpl,
                "mod.admin.views.layouts." . $tpl,
            );
        } else {
            $layouts = array(
                "mod.{$module}.views.layouts." . $tpl,
                "webroot.themes.{$theme}.views.{$module}.layouts." . $tpl,
                "webroot.themes.{$theme}.views.layouts." . $tpl,
            );
        }

        foreach ($layouts as $layout) {
            if (file_exists(Yii::getPathOfAlias($layout) . '.php')) {
                $render = $layout;
                break;
            }
        }

        Yii::app()->controller->renderPartial($render, $array, false, false);
    }

}