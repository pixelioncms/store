<h3>Последние поступления</h3>
    
 <div class="wrapper"> 
<?php
$this->widget('ListView', array(
    'dataProvider' => $provider,
    'ajaxUpdate' => true,
    'template' => '{items}',
    'itemView' => '_view',
   
));

?>
 </div>