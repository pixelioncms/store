<?php

/**
 * WidgetFormModel class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CModel
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class WidgetFormModel extends CModel
{

    public function attributeNames()
    {
        return array();
    }

    public function getSettings($obj)
    {
        return Yii::app()->settings->get($obj);
    }

    public function getConfigurationFormHtml($obj)
    {
        // Yii::import('app.widget_config.*');
        $className = basename(Yii::getPathOfAlias($obj));
        $this->attributes = (array) $this->getSettings($className);
        if (method_exists($this, 'registerScript')) {
            $this->registerScript();
        }
        $form = new WidgetForm($this->getForm(), $this);

        return $form;
    }

    public function saveSettings($obj, $postData)
    {
        $this->setSettings($obj, $postData[get_class($this)]);
    }

    public function setSettings($obj, $data)
    {

        if ($data) {
            $className = basename(Yii::getPathOfAlias($obj));

            //$cache = Yii::app()->cache->get(md5($className));
           // if (isset($cache)) {
           //     Yii::app()->cache->delete($className);
           // }
            Yii::app()->settings->set($className, $data);
        }
    }

}
