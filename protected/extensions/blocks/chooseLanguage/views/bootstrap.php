<li class="dropdown">
    <?php if (count($language->getLanguages()) > 1) { ?>
    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <?= Html::image('/uploads/language/' . $language->active->flag_name, $language->active->name); ?>
    </a>
    <?php }else{ ?>
        <a href="javascript:void(0);">
            <?= Html::image('/uploads/language/' . $language->active->flag_name, $language->active->name); ?>
        </a>
    <?php } ?>
    <?php if (count($language->getLanguages()) > 1) { ?>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <?php
            foreach ($language->getLanguages() as $lang) {
                $classLi = ($lang->code == Yii::app()->language) ? $lang->code . ' active' : $lang->code;
                $link = ($lang->is_default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();
                //Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name), $link, array('title' => $lang->name));
                ?>
                <li class="nav-item">
                    <?php
                    echo Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name) . ' ' . $lang->name, $link, array('class' => 'nav-link'));
                    ?>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</li>
