
<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('cookie');


$config = Yii::app()->settings->get('shop');
/**
 * @var $this FilterWidget
 */
/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 *        'title'=>'Filter Title',
 *        'selectMany'=>true, // Can user select many filter options
 *        'filters'=>array(array(
 *            'title'      => 'Title',
 *            'count'      => 'Products count',
 *            'queryKey'   => '$_GET param',
 *            'queryParam' => 'many',
 *        ))
 *  );
 */
echo Html::openTag('div', array('id' => 'filters'));
// Render active filters
echo Html::beginForm(array('/shop/category/view', 'seo_alias' => $this->model->full_path), 'POST', array('id' => 'filter-form'));


// Currency selected filters
echo Html::openTag('div', array('id' => 'ajax_filter_current'));
echo $this->render('_current', array(), true);
echo Html::closeTag('div');
// Filter by prices
if ($config->filter_enable_price)
    echo $this->render('_price', array('config' => $config, 'prices' => $prices), true);

// Filter by manufacturer

if ($config->filter_enable_brand) {
    echo $this->render('_manufacturer', array(
        'config' => $config,
        'manufacturers' => $manufacturers,
        'attributes' => $attributes
    ), true);
}
// Filters by attributes
echo $this->render('_attributes', array(
    'config' => $config,
    'attributes' => $attributes
), true);


//echo Html::submitButton('Применить',array('class'=>'btn btn-success btn-submit-filter'));
echo Html::endForm();
echo Html::closeTag('div');

?>
