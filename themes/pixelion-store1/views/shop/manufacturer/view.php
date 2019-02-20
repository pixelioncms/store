<div class="clearfix">
    <h1>Производитель <?php echo Html::encode($this->dataModel->name); ?></h1>
    <?= Html::image($this->dataModel->getImageUrl('100x100'),Html::encode($this->dataModel->name),array('class'=>'img-fluid img-manufacturer')); ?>

    <?php if (!empty($this->dataModel->description)) { ?>
         <?php echo $this->dataModel->description ?>

    <?php } ?>
</div>


<div class="clearfix">
    <?php


    $this->widget('ListView', array(
        'dataProvider' => $provider,
        'cssFile' => false,
        'ajaxUpdate' => true, //$ajaxUpdate
        'itemsCssClass' => 'items clearfix',
        'template' => '{items} {pager}',
        'enableHistory' => true,
        'itemsCssClass' => 'items row clearfix',
        'htmlOptions' => array('class' => ''),
        'itemView' => 'current_theme.views.shop.category._view_grid',
        'pagerCssClass' => 'text-center clearfix',
        'sortableAttributes' => array(
            'name', 'price'
        ),
    ));
    ?>
</div>

