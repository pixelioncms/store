<script>
    var testurl = '<?= Yii::app()->request->url; ?>';
var url;

    function loadFilters(that,formid) {
       /* var form = $(formid).serialize();
        console.log(form);
        if ($(that).val() === '') {
            url = '/admin/csv/default/export';
        } else {
            url = '?manufacturer_id=' + $(that).val();
        }
       // window.location = '/admin/csv/default/export'+url;
        window.location = '/admin/csv/default/export?'+form;*/
    }

    $(function(){
        $('#priceExportForm').change(function(){
            var form = $(this).serializeArray();
            var newArray = [];
            $.each(form, function(i, obj) {
                newArray.push('{"asd":"sad"}');
                if(obj.name === 'attributes[]' || obj.name === 'token'){
                   // delete form[i];
                    newArray.splice(form[i], 1);
                }

            });
           // delete form['token'];
            console.log(newArray);

            url = 'manufacturer_id=' + $('#manufacturer_id').val();
            //window.location = '/admin/csv/default/export?'+url;
        });
    });

</script>



<?php
if (!Yii::app()->request->isAjaxRequest) {
    Yii::app()->tpl->openWidget(array(
        'title' => $this->pageName,
    ));
}
?>
<?php

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'priceExportForm',
   // 'method'=>'GET',
    'htmlOptions' => array()
));

?>

<div class="form-group row">
    <div class="col-sm-4"><?= Html::label('Производитель', 'manufacturer_id'); ?></div>
    <div class="col-sm-8">
        <?php
        echo Html::dropDownList('manufacturer_id', Yii::app()->request->getParam('manufacturer_id'), CMap::mergeArray(array('all' => 'Все производители'), Html::listData(ShopManufacturer::model()->findAll(), 'id', 'name')),
            array(
                'class' => 'form-control',
                'onChange' => 'loadFilters(this,"#priceExportForm")'
            ));
        ?>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-4"><?= Html::label('switch', 'switch'); ?></div>
    <div class="col-sm-8">
        <?php
        echo Html::dropDownList('switch', Yii::app()->request->getParam('switch'), array(0 => 'Только скрытые', 1 => 'Только показанные'),
            array(
                'class' => 'form-control',
                'onChange' => 'loadFilters(this)',
                'empty' => '--- Любой ---'
            ));
        ?>
    </div>
</div>
<?php
if ($dataProvider) {



    $this->widget('ButtonPager', array(
        // 'currentPage'=>$pages->getCurrentPage(),
        // 'itemCount' => $dataProvider->totalItemCount,
        // 'pageSize' => $dataProvider->pagination->pageSize,
        'pages' => $pagination,
        'header' => '',
        'htmlOptions' => array('class' => 'pagination'),
    ));
}
?>


<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th></th>
        <th><?= Yii::t('app', 'NAME') ?></th>
        <th><?= Yii::t('app', 'ID') ?></th>
    </tr>
    </thead>
    <?php
    foreach ($importer->getExportAttributes('eav_') as $k => $v) {
        echo '<tr>';
        echo '<td align="left" width="10px"><input type="checkbox" checked name="attributes[]" value="' . $k . '"></td>';
        echo '<td align="left">' . CHtml::encode(str_replace('eav_', '', $k)) . '</td>';
        echo '<td align="left">' . $v . '</td>';

        echo '</tr>';
    }
    ?>
</table>


<?php
echo Html::submitButton('exporting');


$this->endWidget(); ?>

<?php

if (!Yii::app()->request->isAjaxRequest)
    Yii::app()->tpl->closeWidget();
?>


