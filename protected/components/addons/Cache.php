<?php

class Cache extends CFileCache
{
    public function __construct()
    {

        $configs = Yii::app()->settings->get('app');

       return 'CFileCache';

    }
}