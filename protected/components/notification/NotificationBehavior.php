<?php

/**
 *
 * @copyright (c) 2018, Semenov Andrew
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @author Semenov Andrew <info@andrix.com.ua>
 *
 * @link http://pixelion.com.ua PIXELION CMS
 * @link http://andrix.com.ua Developer
 *
 */
class NotificationBehavior extends CActiveRecordBehavior
{

    public $title;
    public $text;
    public $icon;

    /**
     * @param $owner
     */
    public function attach($owner)
    {
        return parent::attach($owner);
    }

    public function afterSave($event)
    {

        $owner = $this->owner;
        $notify = new NotificationModel;
        $notify->title = $this->title;
        $notify->save(false, false, false);
        return true;
    }


}
