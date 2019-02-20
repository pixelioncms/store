<div class="card bg-light">
    <div class="card-header">

        <h5><?php echo (isset($title)) ? $title : null ?></h5>
        <?php if (isset($options)) { ?>


            <div class="dropdown card-option" id="grid-options">
                <?php //echo Html::link('<i class="icon-add"></i> Добавить товар', '#', array('class'=>'btn 2btn-sm btn-outline-2success btn-link')); ?>
                <?php

                foreach ($buttons as $btn) {
                    $btn['htmlOptions'] = (isset($btn['htmlOptions'])) ? $btn['htmlOptions'] : array();
                    if(isset($btn['url'])){
                        echo Html::link($btn['label'], $btn['url'], $btn['htmlOptions']);
                    }else{

                    }

                 } ?>

                <a href="javascript:void(0);" data-toggle="dropdown" class="dropdown-toggle btn btn-link">
                    <i class="icon-settings"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php
                    foreach ($options as $opt) {
                        $opt['htmlOptions'] = (isset($opt['htmlOptions'])) ? $opt['htmlOptions'] : array('class' => 'nav-link');
                        ?>
                        <li class="nav-item"><?= Html::link('<i class="' . $opt['icon'] . '"></i> ' . $opt['label'], $opt['href'], $opt['htmlOptions']) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>


    </div>

    <div class="card-body">
