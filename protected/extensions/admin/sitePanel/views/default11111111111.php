<?php
$dataModel = Yii::app()->controller->adminModel();
?>

<script>
    $(function () {
       /* if ($.cookie('ap') === 'true') {
            $('.ap-navbar').addClass('open');
            $('#ap-navbar').removeClass('hidden');
        } else {
            $('.ap-navbar').removeClass('open');
            $('#ap-navbar').addClass('hidden');
        }
        $('.ap-navbar-brand').click(function () {
            $('.ap-navbar').toggleClass('open');
            $('#ap-navbar').toggleClass('hidden');
            $.cookie('ap', $('.ap-navbar').hasClass('open'), {expires: 7});
        });*/
    });
</script>


<nav class="navbar ap-navbar-expand-sm ap-navbar-light bg-dark ap-fixed-<?= $this->pos; ?>">
    <div class="ap-container-fluid">
    <a class="ap-navbar-brand" href="#"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ap-navbar" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="ap-navbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        <div class=" my-2 my-md-0">
            das
        </div>
    </div>
    </div>
</nav>


<nav class="ap-navbar ap-navbar-expand-sm ap-navbar-light bg-light ap-fixed-<?= $this->pos; ?> d-none" id="ap">
    <div class="ap-container-fluid">

            <button type="button" class="ap-navbar-toggler collapsed" data-toggle="collapse" data-target="#ap-navbar" aria-expanded="false" aria-controls="ap-navbar">
                <span class="ap-navbar-toggler-icon"></span>
            </button>


        <div id="ap-navbar" class="collapse navbar-collapse">
            <?php if(isset($menu)){ ?>
            <ul class="ap-navbar-nav mr-auto">

                <?php foreach ($menu as $row) { ?>
                    <?php if (isset($row['items'])) { ?>
                        <li <?php echo (isset($row['items'])) ? 'class="nav-item dropdown "' : 'class="panelObj"'; ?>>
                            <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="nav-link ap-dropdown-toggle" href="<?php echo (isset($row['url']))?CHtml::normalizeUrl($row['url']):null ?>">
                                <?= $row['icon'] ?> <?= $row['label'] ?></a>
                                <?php if (isset($row['items'])) { ?>
                                <ul class="ap-dropdown-menu">
                                    <?php foreach ($row['items'] as $rowItems) { ?>
                                        <?php if ($rowItems['visible'] == true) { ?>
                                            <li class="ap-dropdown-item>
                                                <a" href="<?php echo CHtml::normalizeUrl($rowItems['url']) ?>"><?= $rowItems['icon'] ?> <?= $rowItems['label'] ?></a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                        </li>
                    <?php } ?>
                <?php } ?>



            </ul>
            <?php } ?>
            <ul class="ap-nav ap-navbar-nav ap-navbar-right">
                <li data-toggle="admin-tooltip" title="<?= Yii::t('PanelWidget.default', 'NEW_COMMENTS', array('{num}' => $this->countComments)) ?>"><a href="<?= Yii::app()->createUrl('/admin/comments') ?>">
                        <i class="icon-comments"></i>
                        <span class="hidden-md hidden-lg hidden-sm"><?= Yii::t('PanelWidget.default', 'COMMENTS') ?></span>
                        <?php if ($this->countComments) { ?><span class="count ap-label ap-label-success"><?= $this->countComments ?></span><?php } ?>
                    </a>
                </li>
                <li data-toggle="admin-tooltip" title="<?= Yii::t('PanelWidget.default', 'NEW_ORDERS', array('{num}' => $this->countOrder)) ?>"><a href="<?= Yii::app()->createUrl('/admin/cart') ?>">
                        <i class="icon-shopcart"></i>
                        <span class="hidden-md hidden-lg hidden-sm"><?= Yii::t('PanelWidget.default', 'ORDERS') ?></span>
                        <?php if ($this->countOrder) { ?><span class="count ap-label ap-label-success"><?= $this->countOrder ?></span><?php } ?>
                    </a>
                </li>
                <li class="dropdown"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="javascript:void(0)"><?= Html::image(Yii::app()->user->getAvatarUrl('25x25'), Yii::app()->user->username, array("width" => "16", "height" => "16", 'class' => 'user-avatar')) ?> <?= Yii::app()->user->username ?></a>
                    <ul class="ap-dropdown-menu">
                        <li><?= Html::link(Yii::t('app', 'UPDATE', 0), array('/admin/users/default/update', 'id' => Yii::app()->user->id)) ?></li>
                        <li><?= Html::link(Yii::t('app', 'ADMIN_PANEL'), array('/admin/users/default/update', 'id' => Yii::app()->user->id)) ?></li>
                        <li role="separator" class="divider"></li>
                        <li><?= Html::link(Yii::t('app', 'LOGOUT'), array('/admin/auth/logout')) ?></li>


                    </ul>
                </li> 
            </ul>
            
            <?php
            if(isset($dataModel) && isset($dataModel->scenario)){
            if ($dataModel->scenario == 'update') { ?>
                <div class="ap-navbar-form ap-navbar-nav" data-toggle="admin-tooltip" title="<?= Yii::t('PanelWidget.default', 'EDIT_MODE') ?>">
                    
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
                    <span class="hidden-md hidden-lg hidden-sm"><?= Yii::t('PanelWidget.default', 'EDIT_MODE') ?></span>
                </div>
            <?php } } ?>



            <?php if (isset($dataModel) && isset($dataModel->scenario)) { ?>
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
                                'class' => 'ap-btn ap-btn-xs ap-btn-default'
                            ));
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</nav>

<script>
    var edit_mode_session = <?= (Yii::app()->user->edit_mode) ?>;
    var isActiveToggle = (edit_mode_session) ? 'on' : 'off';
    var toggle = $('#edit_mode');



    $(function () {
        toggle.bootstrapToggle(isActiveToggle);
        /*$('#label_edit_mode').click(function () {
         var ch = toggle.prop('checked');
         if (ch === true) {
         toggle.bootstrapToggle('off');
         } else {
         toggle.bootstrapToggle('on');
         }
         });*/

        $('[data-toggle="admin-tooltip"]').tooltip({placement: '<?= $this->posTooltip; ?>'});


        toggle.change(function () {
            $.ajax({
                url: '/admin/ajax/ap.action',
                type: 'POST',
                data: {e: ($(this).prop('checked')) ? 1 : 0},
                dateType: 'json',
                success: function (data) {
                    //  console.log(data.message);
                    location.reload();
                }
            });
            // var state = History.getState();
            // var isChecked = $(this).prop('checked');
            // if (isChecked === true) {
            //     $(this).bootstrapToggle('off');
            // } else {
            //     $(this).bootstrapToggle('on');
            // }
            //console.log(state.url);
            // History.pushState({edit_mode: isChecked}, $('title').text(), '?edit_mode=' + isChecked);
            // History.pushState({'edit_mode': isChecked}, null,state.hash);
            // location.reload();
            //$.fn.yiiGridView.update(grid_id);

        });
    });
</script>