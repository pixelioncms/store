


<div class="card card-catalog">
    <div class="card-header">Каталог</div>
    <div class="card-body">
        <?php if (isset($result['items'])) { ?>
            <ul class="nav">
                <?php foreach ($result['items'] as $item) { ?>
                    <?php
                    if (isset($item['items'])) {
                        $href = '#';
                        $datatoggle = 'dropdown';
                        $class = '';
                    } else {
                        $datatoggle = '';
                        $href = Yii::app()->createUrl($item['url'][0], array('seo_alias' => $item['url']['seo_alias']));
                        $class = 'no-arrow';
                    }
                    ?>
                    <li class="dropdown dropright">
                        <a href="<?= $href; ?>" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false"
                           data-toggle="<?= $datatoggle ?>"><?= $item['label'] ?></a>
                        <?php if (isset($item['items'])) { ?>
                            <div class="dropdown-menu">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            sadasdasd
                                        </div>
                                        <div class="col">
                                            sadasdasd
                                        </div>
                                        <div class="col">
                                            sadasdasd
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $i = 0;
                                foreach ($item['items'] as $subitem) {
                                    $i++;
                                    // echo Html::tag('li', array('class' => 'dropdown-item'), Html::link($subitem['label'], $subitem['url']), true);
                                }
                                ?>


                            </div>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScript('categories-widget', "
    $(function () {
        $('.card-catalog2 .dropdown').on('show.bs.dropdown', function () {
            var catalogWidth = $('.card-catalog').width();
            var containerWidth = $('.container').width();
            $('.dropdown-menu',this).addClass('ssssssssss');
            console.log(catalogWidth+'-'+containerWidth);
        });
    });
", CClientScript::POS_END);
?>
