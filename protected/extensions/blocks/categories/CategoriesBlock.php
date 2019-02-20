<?php
/**
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 */

Yii::import('mod.core.models.CategoriesModel');

class CategoriesBlock extends BlockWidget
{

    private static $menuTree = array();

    public function getTitle()
    {
        return Yii::t('CategoriesBlock.default', 'NAME');
    }

    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = '`t`.`parent_id`= :root AND `t`.`module`= :mod';
        $criteria->params = array(':root' => 0, ':mod' => 'news');
        $criteria->order = '`t`.`ordern` DESC';

        $model = new ActiveDataProvider('CategoriesModel', array(
            'criteria' => $criteria,
        ));

        $this->render($this->skin, array('model' => $model));
    }

    public function getMenuTree()
    {
        //  if (empty(self::$menuTree)) {

        $criteria = new CDbCriteria();
        $criteria->condition = '`t`.`parent_id`= :root';
        $criteria->params = array(':root' => 0);
        $criteria->order = '`t`.`ordern` DESC';
        $criteria->scopes = 'module';
        $rows = new ActiveDataProvider('CategoriesModel', array('criteria' => $criteria));

        foreach ($rows->getData() as $key => $item) {
            self::$menuTree[] = self::getMenuItems($item);
        }
        // }
        return self::$menuTree;
    }

    private static function getMenuItems($modelRow)
    {
        $module = Yii::app()->controller->module->id;
        if (!$modelRow)
            return;

        if (isset($modelRow->childs)) {
            $chump = self::getMenuItems($modelRow->childs);
            if ($chump != null)
                return array(
                    'label' => $modelRow->name,
                    'url' => Yii::app()->createUrl($module, array('category' => $modelRow->seo_alias)),
                    'active' => self::isActive($modelRow->seo_alias),
                    'items' => $chump
                );
            else
                return array(
                    'label' => $modelRow->name,
                    'active' => self::isActive($modelRow->seo_alias),
                    'url' => Yii::app()->createUrl($module, array('category' => $modelRow->seo_alias))
                );
        } else {
            if (is_array($modelRow)) {
                $arr = array();
                foreach ($modelRow as $leaves) {
                    $arr[] = self::getMenuItems($leaves, $counter);
                }
                return $arr;
            } else {
                return array(
                    'label' => $modelRow->name,
                    'active' => self::isActive($modelRow->seo_alias),
                    'url' => Yii::app()->createUrl($module, array('category' => $modelRow->seo_alias))
                );
            }
        }
    }

    private static function isActive($alias)
    {
        if (isset($_GET['category'])) {
            if ($_GET['category'] == $alias) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
