<div class="card">
    <div class="card-header"><i class="icon-menu"></i> Каталог</div>
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
                    <li class="dropdown menu-item">
                        <a href="<?= $href; ?>" class="dropdown-toggle <?= $class ?>" data-hover="<?= $datatoggle ?>" data-toggle="<?= $datatoggle ?>"><?= $item['label'] ?></a>
                        <?php if (isset($item['items'])) { ?>
                            <ul class="dropdown-menu">
                                <li class="yamm-content">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <ul class="links list-unstyled">
                                                <?php
                                                $i = 0;
                                                foreach ($item['items'] as $subitem) {
                                                    $i++;
                                                    echo Html::tag('li', array(), Html::link($subitem['label'], $subitem['url']), true);
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </li>               
                            </ul>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>

