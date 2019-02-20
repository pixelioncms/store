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




$tpl = '    <div id="logo">{time}
        <a href=""><img src="{*logo.image*}" alt="{*logo.alt*}"></a>
    </div>

    <div id="menu">
       
        /* цикл: */
       
        {%*menu*}
            <a href="{*menu:url*}">{*menu:title*}</a><br/>
                    {%*menu:submenu*}
            ----<a href="{*menu:submenu:url*}">{*menu:submenu:title*}</a><br/>
        {%}
        {%}
   
    </div>
   {if!*content*} 
   default.css 
   {else}
   special.css 
   {if*test2*} 
   123 
   {else}
   321 {endif}

{endif}
   
<br>


   
    <div id="footer">
        webew.ru &copy; {*year*}
    </div>'; 
$f = new websun_helper;
$html2 = $f->websun_parse_template($data, $tpl); 


echo $html;

/*
$tpl = TemplateMailModel::model()->findByPk(1);
$tpl->setOptions = $tpl->getModelByPk(1,'User');
$res = $tpl->getBody();
echo $res;*/
//$ss = $tpl->getModelByPk(1,'User');
//print_r($ss);


?>