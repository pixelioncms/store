
<?php


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'autoColumns'=>false,
    'filter'=>$model,
    'name'=>$this->pageName,
    'customActions' => array(
        array(
            'label' => Yii::t('CommentsModule.core', 'Подтвержден'),
            'url' => '#',
            'linkOptions' => array(
                'onClick' => 'return setCommentsStatus(1, this);',
            )
        ),
        array(
            'label' => Yii::t('CommentsModule.core', 'Ждет одобрения'),
            'url' => '#',
            'linkOptions' => array(
                'onClick' => 'return setCommentsStatus(0, this);',
            )
        ),
        array(
            'label' => Yii::t('CommentsModule.core', 'Спам'),
            'url' => '#',
            'linkOptions' => array(
                'onClick' => 'return setCommentsStatus(2, this);',
            )
        ),
    ),
    'columns' => array(
        array('class' => 'CheckBoxColumn'),
        array(
            'name' => 'text',
            'value' => 'CMS::truncate("$data->text", 100)',
            'htmlOptions'=>array('class'=>'textL')
        ),
        array(
            'name' => 'switch',
            'filter' => Comments::getStatuses(),
            'value' => '$data->statusTitle',
        ),
        array(
            'name' => 'owner_title',
            'filter' => false
        ),
        array(
            'name' => 'date_create',
            'value' => 'CMS::date("$data->date_create")'
        ),
        'ip_create',

        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));

?>

