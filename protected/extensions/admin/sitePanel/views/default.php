<?php
$dataModel = Yii::app()->controller->adminModel();
Yii::app()->clientScript->registerScript("admin-panel-".$this->id,"
    $(function () {
        if ($.cookie('ap') === 'true') {
            $('.ap-navbar').addClass('ap-toggle');
        } else {
            $('.ap-navbar').removeClass('ap-toggle');
        }
        $('.ap-navbar-brand').click(function () {
            $('.ap-navbar').toggleClass('ap-toggle');
            $.cookie('ap', $('.ap-navbar').hasClass('ap-toggle'), {expires: 7});
        });
    });
",CClientScript::POS_END);
?>

<div id="ap">
    <nav class="ap-navbar ap-navbar-expand-md ap-navbar-light ap-fixed-<?= $this->pos; ?>">
        <div class="ap-container-fluid">
            <a class="ap-navbar-brand" href="javascript:void(0)"><i class="icon-logo"></i></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ap-navbar"
                    aria-controls="ap-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="ap-navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="ap-navbar">
                <ul class="navbar-nav mr-auto">


                    <?php foreach ($menu as $row) { ?>
                        <?php if (isset($row['items'])) { ?>
                            <li <?php echo (isset($row['items'])) ? 'class="dropdown "' : 'class="panelObj"'; ?>>
                                <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
                                   class="dropdown-toggle"
                                   href="<?php echo (isset($row['url'])) ? CHtml::normalizeUrl($row['url']) : null ?>">
                                    <?= $row['icon'] ?> <?= $row['label'] ?></a>
                                <?php if (isset($row['items'])) { ?>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($row['items'] as $rowItems) { ?>

                                            <?php if (isset($rowItems['visible']) && $rowItems['visible'] == true) { ?>
                                                <li class="dropdown-item">
                                                    <a href="<?php echo CHtml::normalizeUrl($rowItems['url']) ?>"><?= $rowItems['icon'] ?> <?= $rowItems['label'] ?></a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>

                            </li>
                        <?php } ?>
                    <?php } ?>






                    <?php
                    if (isset($dataModel) && isset($dataModel->scenario) && false) {
                        if ($dataModel->scenario == 'update') { ?>
                            <div class="ap-navbar-nav" data-toggle="admin-tooltip"
                                 title="<?= Yii::t('PanelWidget.default', 'EDIT_MODE') ?>">

                                <?php
                                echo Html::checkBox('edit_mode', Yii::app()->request->getParam('edit_mode') ? true : false, array(
                                    'data-toggle' => 'toggle',
                                    'data-on' => "Вкл",
                                    'data-off' => "Выкл",
                                    'data-offstyle' => 'default',
                                    'data-onstyle' => 'success',
                                    'data-size' => 'mini',
                                    'data-width' => 50
                                ));
                                ?>
                                <span class="d-none-md d-none-lg d-none-sm"><?= Yii::t('PanelWidget.default', 'EDIT_MODE') ?></span>
                            </div>



                        <?php }
                    } ?>



                    <?php if (isset($dataModel) && isset($dataModel->scenario) && false) { ?>
                        <div class="ap-navbar-form ap-navbar-nav">
                            <div class="ap-btn-group2">


                                <?php
                                if ($dataModel->scenario == 'update') {
                                    //Yii::t(ucfirst($dataModel::MODULE_ID).'Module.default','UPDATE')
                                    echo Html::link('<i class="icon-add"></i>', $dataModel->getCreateUrl(), array(
                                        'title' => Yii::t(ucfirst($dataModel::MODULE_ID) . 'Module.default', 'CREATE'),
                                        'target' => '_blank',
                                        'data-toggle' => 'admin-tooltip',
                                        'class' => 'ap-btn ap-btn-xs ap-btn-success'
                                    ));
                                }
                                ?>
                                <?php
                                if ($dataModel->scenario == 'update') {
                                    echo Html::link('<i class="icon-edit"></i>', $dataModel->getUpdateUrl(), array(
                                        'title' => Yii::t('app', 'UPDATE', 0),
                                        'target' => '_blank',
                                        'data-toggle' => 'admin-tooltip',
                                        'class' => 'ap-btn ap-btn-xs ap-btn-light'
                                    ));
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <li data-toggle="admin-tooltip" title="<?= Yii::t('PanelWidget.default', 'EDIT_MODE') ?>">
                        <div class="ap-navbar-nav">
                            <?= Html::checkBox('edit_mode', Yii::app()->user->isEditMode, array('id' => 'ap-edit_mode', 'onChange' => 'editmode(' . Yii::app()->user->id . '); return false;')); ?>
                            <label for="ap-edit_mode"
                                   class="d-none-md d-none-lg d-none-sm"><?= Yii::t('PanelWidget.default', 'EDIT_MODE') ?></label>
                        </div>
                    </li>
                    <li>
                    <div class="switch">
                        <input id="cmn-toggle-4" class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                        <label for="cmn-toggle-4"></label>
                    </div>
                    </li>
                </ul>


                <div class="ap-navbar-nav-right">
                    <ul class="navbar-nav mr-auto">
                        <?php if (Yii::app()->hasModule('comments')) { ?>
                            <li data-toggle="admin-tooltip"
                                title="<?= Yii::t('PanelWidget.default', 'NEW_COMMENTS', array('{num}' => $this->countComments)) ?>">
                                <a href="<?= Yii::app()->createUrl('/admin/comments') ?>">
                                    <i class="icon-comments"></i>
                                    <span class="hidden-md hidden-lg hidden-sm"><?= Yii::t('PanelWidget.default', 'COMMENTS') ?></span>
                                    <?php if ($this->countComments) { ?><span
                                            class="count ap-badge ap-badge-success"><?= $this->countComments ?></span><?php } ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (Yii::app()->hasModule('cart')) { ?>
                            <li data-toggle="admin-tooltip"
                                title="<?= Yii::t('PanelWidget.default', 'NEW_ORDERS', array('{num}' => $this->countOrder)) ?>">
                                <a href="<?= Yii::app()->createUrl('/admin/cart') ?>">
                                    <i class="icon-shopcart"></i>
                                    <span class="hidden-md hidden-lg hidden-sm"><?= Yii::t('PanelWidget.default', 'ORDERS') ?></span>
                                    <?php if ($this->countOrder) { ?><span
                                            class="count ap-badge ap-badge-success"><?= $this->countOrder ?></span><?php } ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="dropdown"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                class="dropdown-toggle"
                                                href="javascript:void(0)"><?= CHtml::image(Yii::app()->user->getAvatarUrl('25x25'), Yii::app()->user->username, array("width" => "16", "height" => "16", 'class' => 'user-avatar')) ?> <?= Yii::app()->user->username ?></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item"><?= CHtml::link('<i class="icon-edit"></i> ' . Yii::t('app', 'UPDATE', 0), array('/admin/users/default/update', 'id' => Yii::app()->user->id), array('class' => 'nav-item')) ?></li>
                                <li class="dropdown-item"><?= CHtml::link('<i class="icon-logo-bold"></i> ' . Yii::t('app', 'ADMIN_PANEL'), array('/admin')) ?></li>
                                <li role="separator" class="ap-dropdown-divider"></li>
                                <li class="dropdown-item"><?= CHtml::link('<i class="icon-exit"></i> ' . Yii::t('app', 'LOGOUT'), array('/users/logout'), array('class' => 'nav-item')) ?></li>


                            </ul>
                        </li>
                    </ul>


                </div>
            </div>
        </div>
    </nav>
</div>
