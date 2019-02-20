<?php

/**
 * Product comments
 *
 * @var $model ShopProduct
 */
Yii::import('mod.comments.models.Comments');

$module = Yii::app()->getModule('comments');
$comments = new Comments('search');

if (!empty($_GET['Comments']))
    $comments->attributes = $_GET['Comments'];

$comments->model = 'mod.shop.models.ShopProduct';
$comments->object_id = $model->id;

// Fix sort url
$dataProvider = $comments->search(array('object_id'=>$model->id,'model'=>'mod.shop.models.ShopProduct'));
$dataProvider->pagination->pageSize = Yii::app()->settings->get('app', 'pagenum');
$dataProvider->sort->route = 'applyCommentsFilter';
$dataProvider->pagination->route = 'applyCommentsFilter';

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'id' => 'productCommentsListGrid',
    'autoColumns'=>false,
    //'filter'       => $comments,
    'ajaxUrl' => Yii::app()->createUrl('/shop/admin/products/applyCommentsFilter', array('id' => $model->id)),
    'enableHistory' => false,
    'enableHeader'=>false,
    'columns' => array(
        array(
            'class' => 'CheckBoxColumn',
        ),
       /* array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->name), array("/comments/admin/comments/update", "id"=>$data->id))',
        ),*/
        array(
            'name' => 'user_email',
        ),
        array(
            'name' => 'text',
            'value' => 'CMS::truncate($data->text, 100)'
        ),
        array(
            'name' => 'switch',
            'filter' => Comments::getStatuses(),
            'value' => '$data->statusTitle',
        ),
        array(
            'name' => 'date_create',
        ),
        // Buttons
        array(
            'class' => 'ButtonColumn',
            'updateButtonUrl' => 'Yii::app()->createUrl("/comments/admin/default/update", array("id"=>$data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/comments/admin/default/delete", array("id"=>$data->id))',
            'template' => '{update}{delete}',
        ),
    ),
));
//if(!$model->isNewRecord && Yii::app()->hasModule('comments'))
   // $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));