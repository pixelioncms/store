<?php if (count($language->getLanguages()) > 1) { ?>
    <ul id="langs">
        <?php
        foreach ($language->getLanguages() as $lang) {

            $class = ($language->active->id == $lang->id) ? 'active' : '';
            $link = ($lang->is_default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();
            ?>
            <li class="<?= $class ?>">
                <?= Html::link($lang->code, $link, array('class' => 'text-uppercase')); ?>
            </li>
        <?php } ?>
    </ul>
<?php } ?>