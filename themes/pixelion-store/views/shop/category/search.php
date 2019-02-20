<?php
if (($q = Yii::app()->request->getParam('q')))
    $result = CHtml::encode($q);
?>


<h1><?= Yii::t('ShopModule.default', 'SEARCH_RESULT', array(
        '{result}' => $result,
        '{count}' => count($provider->getData())
    )); ?></h1>


    <?php
    $this->renderPartial('_ajax',array('provider'=>$provider,'itemView'=>$itemView));
    /*$this->widget('ListView', array(
        'dataProvider' => $provider,
        'ajaxUpdate' => false,
        'template' => '{items} {pager}',
        'itemView' => '_view_grid',
        'itemsCssClass' => 'items row clearfix',
        'sortableAttributes' => array(
            'name', 'price'
        ),
        'afterAjaxUpdate' => 'function(id,data){
            $("span > input").rating({"readOnly":true});
        }',
        'emptyText' => Yii::t('ShopModule.default', 'EMPTY_SEARCH_TEXT', array('{result}' => $result)),
        'pager' => array(
            'htmlOptions' => array('class' => 'pagination'),
            'header' => '',
            'nextPageLabel' => 'Следующая »',
            'prevPageLabel' => '« Предыдущая',
            'prevPageLabel' => '« Предыдущая',
            'prevPageLabel' => '« Предыдущая',
        )
    ));*/
    ?>


