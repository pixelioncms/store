<?php

$itemViewClass = explode('.',$itemView);
$itemViewClass = end($itemViewClass);
$this->widget('ListView', array(
    'id' => 'shop-products',
    'dataProvider' => $provider,
    'cssFile' => false,
    'ajaxUpdate' => true, //$ajaxUpdate
    'enableHistory' => true,
    'ajaxType'=>'POST',
    'htmlLoading'=>'<div class="osahanloading"></div>',
    'itemsCssClass' => 'items row2 clearfix '.$itemViewClass,
    'template' => '<div>{summary}</div>{items} {pager}', //{sorter}
    'htmlOptions' => array('class' => ''),
    'itemView' => $itemView,
    'sortableAttributes' => array(
        'name', 'price'
    ),
    'afterAjaxUpdate' => 'function(id,data){
        $("span > input").rating({"readOnly":true});
    }',
    'pager' => array(
        'htmlOptions' => array('class' => 'pagination justify-content-center')
    )
    /* 'pager' => array(
         'class' => 'SuperPager',
         'enableShowmore' => true,
         'header' => '',
         'nextPageLabel' => 'Следующая »',
         'prevPageLabel' => '« Предыдущая',
         'firstPageLabel' => '«',
         'lastPageLabel' => '»',
     )*/
    /* 'pager' => array(
      'gridid' => 'shop-products',
      'class' => 'SuperPager',
      'autoShowMoreLabel' => true,
      //'showMoreLabel'=>Yii::t('NewsModule.default','PAGER_SHOWMORE'),
      'showMoreLabel' => CMS::GetFormatWord('ShopModule.default', 'PAGER_SHOWMORE', Yii::app()->cart->countItems()),
      'lastPageLabel' => false,
      'nextPageLabel' => false,
      'prevPageLabel' => false,
      'firstPageLabel' => false,
      'header' => '',
      ),
      'beforeAjaxUpdate' => "js:function(){
      $('.showmore-block a').html('Загрузка...').addClass('loading');
      $.fn.yiiListView('update',{test:1});
      $('#showmore').click(function(){
      console.log($(this).text());
      });

      }",
      'afterAjaxUpdate' => "js:function(){


      }" */
));
?>