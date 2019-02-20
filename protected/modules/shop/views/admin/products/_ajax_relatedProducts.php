<?php if (isset($product->relatedProducts)){ ?>
    <table class="table table-striped table-bordered" id="relatedProductsTable">
        <?php foreach ($product->relatedProducts as $related) { ?>
            <tr>
            <input type="hidden" value="<?php echo $related->id ?>" name="RelatedProductId[]">
            <td class="image text-center relatedProductLine<?php echo $related->id ?>"><?php echo $related->renderGridImage(); ?></td>
            <td><?php
                echo Html::link($related->name, array('/admin/shop/products/update', 'id' => $related->id), array(
                    'target' => '_blank'
                ));
                ?></td>
            <td class="text-center"><a class="btn btn-danger" href="javascript:void(0)" onclick="$(this).parents('tr').remove();"><?php echo Yii::t('app', 'DELETE', 0) ?></a></td>
        </tr>
    <?php } ?>
    </table>
    <br/><br/>
<?php } ?>



<?php
/**
 * Related products tab
 */

Yii::app()->getClientScript()->registerScriptFile($this->module->assetsUrl . '/admin/relatedProductsTab.js');

if (!isset($model)) {
    $model = new ShopProduct('search');
    $model->exclude = $exclude;
}

// Fix sort and pagination urls
$dataProvider = $model->search();
$dataProvider->sort->route = 'applyProductsFilter';
$dataProvider->pagination->route = 'applyProductsFilter';

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'ajaxUrl' => Yii::app()->createUrl('/shop/admin/products/applyProductsFilter', array('exclude' => $exclude)),
    'id' => 'RelatedProductsGrid',
    'genId' => false,
    'template' => '{items}{summary}',
    'enableCustomActions' => false,
    'autoColumns' => false,
    'enableHeader' => false,
    'enableHistory' => false,
    'selectableRows' => 0,
    //'filter'             => $model,
    'columns' => array(
        /*array(
            'name' => 'id',
            'type' => 'text',
            'value' => '$data->id',
            'filter' => Html::textField('RelatedProducts[id]', $model->id),
            'htmlOptions' => array('class' => 'text-center')
        ),*/
        array(
            'name' => 'image',
            'type' => 'html',
            'htmlOptions' => array('class' => 'image text-center'),
            'filter' => false,
            'value' => '$data->renderGridImage()'
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->name), array("update", "id"=>$data->id), array("target"=>"_blank","class"=>"product-name","data-id"=>$data->id))',
            'filter' => Html::textField('RelatedProducts[name]', $model->name)
        ),
        array(
            'name' => 'sku',
            'value' => '$data->sku',
            'filter' => Html::textField('RelatedProducts[sku]', $model->sku)
        ),
        array(
            'name' => 'price',
            'value' => '$data->price',
            'filter' => Html::textField('RelatedProducts[price]', $model->price),
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'class' => 'CLinkColumn',
            'header' => '',
            'label' => Yii::t('app', 'CREATE', 0),
            'urlExpression' => '$data->id."/".Html::encode($data->name)',
           'linkHtmlOptions' => array('class' => 'btn btn-success'),
            'htmlOptions' => array(
                'onClick' => 'return AddRelatedProduct(this);',
                'class' => 'text-center',
            ),
        ),
    ),
));
