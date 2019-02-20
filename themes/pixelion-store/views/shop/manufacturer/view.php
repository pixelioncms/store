<div class="row">
    <div class="col-md-3 sidebar">
        <?php
        $this->widget('mod.shop.widgets.filter2.FilterWidget', array(
            'model' => $this->dataModel,
            'attributes' => $this->eavAttributes,
            'countAttr' => true,
        ));
        ?>


    </div>
    <div class="col-md-9">

        <h1>Производитель <?php echo Html::encode($this->dataModel->name); ?></h1>
        <?= Html::image($this->dataModel->getImageUrl('100x100'), Html::encode($this->dataModel->name), array('class' => 'img-fluid img-manufacturer')); ?>

        <?php
        if (!empty($this->dataModel->description)) {
            echo $this->dataModel->description;

        }

        echo $this->renderPartial('current_theme.views.shop.category._ajax', array(
            'provider' => $provider,
            'itemView' => 'current_theme.views.shop.category._view_grid'
        ));


        ?>
    </div>
</div>
