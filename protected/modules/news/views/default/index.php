<h1><?= $this->pageName; ?></h1>
<?php

$this->widget('ListView', array(
    'dataProvider' => $provider->search(),
    'id' => 'news-list',
    'ajaxUpdate' => true,
    'template' => '{items} {pager}',
    'itemView' => '_list',
    'pagerCssClass' => 'page text-center',
    'pager' => array(
        'header' => '',
     ),
));
?>

