<?php 


$data['site_name'] = Yii::app()->settings->get('app', 'site_name');
$data['admin_email'] = Yii::app()->settings->get('app', 'admin_email');

$data['year']=date('Y',CMS::time());
$data['time']=date('H:i',CMS::time());
$data['host']=Yii::app()->request->serverName;

$data['logo'] = array(
    'image' => 'i/logo.gif',
    'alt' => 'Логотип webew.ru',
    'test'=>array('das'=>'111111')
    );

$data['menu'] = array(
    array('url' => 'css', 'title' => 'CSS'),
    array('url' => 'php', 'title' => 'PHP'),
    array('url' => 'seo', 'title' => 'Интернет-маркетинг'),
    array('url' => 'c', 'title' => 'C/C++','submenu'=>array(
    array('url' => 'css', 'title' => '123123'),
    array('url' => 'php', 'title' => 'PHP1231'),
    array('url' => 'seo', 'title' => 'Интернет-маркетинг'),
    array('url' => 'c', 'title' => 'C/C++')
    ))
    );

$data['content'] = false;
$data['test2'] = false;
