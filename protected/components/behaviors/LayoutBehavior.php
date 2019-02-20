<?php

class LayoutBehavior extends CBehavior {

    public function initLayout() {
        $owner = $this->getOwner();

        if (empty($owner->layout)) {
            if (isset(Yii::app()->theme)) {
                $theme = Yii::app()->theme->getName();
            } else {
                Yii::log(__CLASS__ . ' Ошибка: Системе не удалось найти указанную тему', 'error');
                // throw new CException(__CLASS__.' Ошибка: Системе не удалось найти указанную тему');
            }
            $module = Yii::app()->controller->module->id;
            $controller = Yii::app()->controller->id;
            $action = Yii::app()->controller->action->id;

            $cacheId = "layout_{$theme}_{$module}_{$controller}_{$action}";

            if (!$owner->layout = Yii::app()->cache->get($cacheId)) {
                $layouts = array(
                    /* level 1 */
                    "webroot.themes.{$theme}.views.{$module}.layouts.{$controller}_{$action}",
                    "mod.{$module}.views.layouts.{$controller}_{$action}",
                    /* end level 1 */
                    /* level 2 */
                    "webroot.themes.{$theme}.views.{$module}.layouts.{$controller}",
                    "mod.{$module}.views.layouts.{$controller}",
                    /* end level 2 */
                    /* level 3 */
                    "webroot.themes.{$theme}.views.{$module}.layouts.default",
                    "mod.{$module}.views.layouts.default",
                    /* end level 3 */
                    /* level 4 */
                    "webroot.themes.{$theme}.views.layouts.default",
                    "mod.{$module}.views.layouts.default",
                    /* end level 4 */
                );

                foreach ($layouts as $layout) {
                    if (file_exists(Yii::getPathOfAlias($layout) . '.php')) {
                        $owner->layout = $layout;
                        break;
                    }
                }
                Yii::log('Cache time: ' . Yii::app()->settings->get('app', 'cache_time'), 'info');
                Yii::app()->cache->set($cacheId, $owner->layout, Yii::app()->settings->get('app', 'cache_time'));
            }
        }
    }

}
