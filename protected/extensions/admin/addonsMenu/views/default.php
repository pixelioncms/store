<?php if (isset($menu)) { ?>
    <div class="breadLinks">
        <ul>
            <?php
            foreach ($menu as $param) {
                $param['visible'] = isset($param['visible']) ? $param['visible'] : true;
                if ($param['visible']) {
                    if (isset($param['items'])) {
                        $htmlOptionsLi = (isset($param['icon'])) ? array('class' => 'dropdown has-icon') : array('class' => 'dropdown has');
                        echo Html::openTag('li', $htmlOptionsLi); //Обязательный класс has.
                        echo Html::link($param['icon'] . ' ' . $param['label'], 'javascript:void(0)', isset($param['linkOptions']) ? $param['linkOptions'] : array(
                            'data-toggle' => "dropdown",
                            'aria-haspopup' => "true",
                            'class'=>'dropdown-toggle',
                            'aria-expanded' => "false"));
                        echo Html::openTag('ul', (isset($param['itemsHtmlOptions']) ? $param['itemsHtmlOptions'] : array('class' => 'dropdown-menu dropdown-menu-right')));
                        if (isset($param['items'])) {
                            foreach ($param['items'] as $sparam) {
                                $sparam['visible'] = isset($sparam['visible']) ? $sparam['visible'] : true;
                                if ($sparam['visible']) {
                                    $htmlOptionsLi = (isset($sparam['icon'])) ? array('class' => 'nav-item') : array();
                                    $sparam['linkOptions'] = isset($sparam['linkOptions']) ? $sparam['linkOptions'] : array('class'=>'nav-link2');
                                    echo Html::openTag('li', $htmlOptionsLi);

                                    echo Html::link($sparam['icon'] . ' ' . $sparam['label'], $sparam['url'], $sparam['linkOptions']);
                                    echo Html::closeTag('li');
                                }
                            }
                        }
                        echo Html::closeTag('li');
                        echo Html::closeTag('ul');
                    } else {
                        $htmlOptionsLi = (isset($param['icon'])) ? array('class' => 'has-icon') : array();
                        $icon = (isset($param['icon'])) ? $param['icon'] : '';
                        $param['linkOptions'] = (isset($param['linkOptions'])) ? $param['linkOptions'] : array();
                        echo Html::openTag('li', $htmlOptionsLi);
                        echo Html::link($icon . ' ' . $param['label'], $param['url'], $param['linkOptions']);
                        echo Html::closeTag('li');
                    }
                }
            }
            ?>
        </ul>
        <div class="clearfix"></div>
    </div>
<?php } ?> 
