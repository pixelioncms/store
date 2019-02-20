
<div id="catalog-left-nav">
    <?= Html::link('Каталог продукции', array('/shop'), array('class' => 'btn btn-danger btn-lg btn-block catalog-title')); ?>
    <div class="test">
        <?php
        echo $this->recursive($result['items']);
        ?>
    </div>
</div>



