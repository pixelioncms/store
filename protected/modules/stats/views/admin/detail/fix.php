<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

echo "<div>";
foreach ($count as $k => $v)
    echo "<b>Всего хитов <font color='#DE3163'>" . str_replace(" :", "", $k) . "</font></b> : $v<br>";
echo "</div>";

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'enablePagination' => true,
    'afterAjaxUpdate' => "function(id, data){
        $('[data-toggle=\"tooltip\"]').tooltip();
    }",
    'columns' => array(
        array(
            'name' => 'refer',
            'header' => 'Referer',
            'type' => 'raw',
        ),
        array(
            'name' => 'ip',
            'header' => 'IP-адрес',
            'type' => 'raw',
        ),
        array(
            'name' => 'host',
            'header' => 'Хост',
            'type' => 'raw',
        ),
        array(
            'name' => 'user_agent',
            'header' => 'User-Agent',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'req',
            'header' => 'Страница',
            'type' => 'raw',
        ),
    )
));
?>


<?php

Yii::app()->tpl->closeWidget();
?>
