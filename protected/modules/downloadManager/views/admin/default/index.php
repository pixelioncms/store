<?php
Yii::app()->tpl->alert('info',Yii::t('DownloadManagerModule.default','INFO_INDEX',array(
    '{link}'=>Html::link(Yii::t('app','MODULES'),array('/admin/app/modules'))
    )),false);

        $data = LicenseCMS::run()->getData();
        $users = (isset($data['http_auth'])) ? $data['http_auth'] : array();
//print_r($users);
var_dump(Yii::app()->user->getState('http_auth'));
?>
<a href="http://test:test@pixelion.loc/admin/downloadManager">test url auth</a>
<?php
$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'enableHeader' => true,
    'name'=>'Список загруженных модулей',
    'autoColumns' => false,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'filename',
            'header' => 'Название файла',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'text-left'),
        ),
        array(
            'name' => 'version',
            'header' => 'Версия',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'name' => 'filesize',
            'header' => Yii::t('app', 'SIZE'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'name' => 'url',
            'header' => Yii::t('app', 'OPTIONS'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
        ),
    )
        )
);
