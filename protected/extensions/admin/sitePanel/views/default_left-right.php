<?php
$dataModel = Yii::app()->controller->adminModel();
?>

<script>
    $(function () {
        $('.ap-navbar-brand').click(function () {
            $('.ap-navbar').toggleClass('open');
            $('#ap-navbar').toggleClass('hidden');
        });
    });
</script>
<nav class="ap-navbar ap-navbar-default ap-navbar-fixed-<?=$this->pos;?>" id="ap">

        <div class="ap-navbar-header">
            <button type="button" class="ap-navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="ap-navbar-brand" href="#"><i class="ap-favicon"></i></a>
        </div>
        <div id="ap-navbar" class="hidden">
            <ul class="ap-nav">

                <?php foreach ($menu as $row) { ?>
                    <?php if (isset($row['items'])) { ?>
                        <li <?php echo (isset($row['items'])) ? 'class="dropdown "' : 'class="panelObj"'; ?>>
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="<?php echo CHtml::normalizeUrl($row['url']) ?>">
                                <?= $row['label'] ?> <span class="caret caret-right pull-right"></span></a>
                                <?php if (isset($row['items'])) { ?>
                                <ul class="ap-dropdown-menu">
                                    <?php foreach ($row['items'] as $rowItems) { ?>
                                        <?php if ($rowItems['visible'] == true) { ?>
                                            <li>
                                                <a href="<?php echo CHtml::normalizeUrl($rowItems['url']) ?>"><span class="<?= $rowItems['icon'] ?>" style="margin-right:5px;"></span><?= $rowItems['label'] ?></a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                        </li>
                    <?php } ?>
                <?php } ?>



            </ul>

            <ul class="ap-nav">
                <li class="dropdown"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="javascript:void(0)">Добро пожаловать <?= Html::image(Yii::app()->user->getAvatarUrl('25x25'), Yii::app()->user->username, array("width" => "16", "height" => "16", 'class' => 'user-avatar')) ?> <?= Yii::app()->user->username ?></a>
                    <ul class="ap-dropdown-menu">
                        <li><?= Html::link(Yii::t('app', 'UPDATE', 0), array('/admin/users/default/update', 'id' => Yii::app()->user->id)) ?></li>
                        <li><?= Html::link('admin panel', array('/admin/users/default/update', 'id' => Yii::app()->user->id)) ?></li>
                        <li role="separator" class="divider"></li>
                        <li><?= Html::link(Yii::t('app', 'LOGOUT'), array('/admin/auth/logout')) ?></li>


                    </ul>
                </li> 
            </ul>
            <?php if ($dataModel->scenario == 'update') { ?>
                <div class="ap-navbar-form">
                    <?php
                    echo Html::checkBox('edit_mode', Yii::app()->request->getParam('edit_mode') ? true : false, array(
                        'data-toggle' => 'toggle',
                        'data-on' => "Вкл",
                        'data-off' => "Выкл",
                        'data-offstyle' => 'default',
                        'data-onstyle' => 'success',
                        //  'data-size' => 'mini',
                        'data-width' => 70
                    ));
                    ?>


                    <label for="edit_mode" class="control-label" id="label_edit_mode">Режим редактирование</label>

                </div>
            <?php } ?>
            <div class="ap-navbar-form">
                <div class="ap-btn-group-wrap">
                <div class="ap-btn-group    ">




                    <?php
                    if ($dataModel->scenario == 'update') {
                        //Yii::t(ucfirst($dataModel::MODULE_ID).'Module.default','UPDATE')
                        echo Html::link('<i class="icon-add"></i>', $dataModel->getCreateUrl(), array(
                            'title' => Yii::t(ucfirst($dataModel::MODULE_ID) . 'Module.default', 'CREATE'),
                            'target' => '_blank',
                            'data-toggle' => 'admin-tooltip',
                            'class' => 'ap-btn ap-btn-success'
                        ));
                    }
                    ?>
                    <?php
                    if ($dataModel->scenario == 'update') {
                        echo Html::link('<i class="icon-edit"></i>', $dataModel->getUpdateUrl(), array(
                            'title' => Yii::t('app', 'UPDATE', 0),
                            'target' => '_blank',
                            'data-toggle' => 'admin-tooltip',
                            'class' => 'ap-btn ap-btn-default'
                        ));
                    }
                    ?>
                </div>
                    </div>
            </div>
        </div><!--/.nav-collapse -->

</nav>


<script>
    var edit_mode_session = <?= ($_SESSION['edit_mode']) ? $_SESSION['edit_mode'] : 0 ?>;
    var isActiveToggle = (edit_mode_session) ? 'on' : 'off';
    var toggle = $('#edit_mode');



    $(document).ready(function () {
        $('#label_edit_mode').click(function () {
            var ch = toggle.prop('checked');
            if (ch === true) {
                toggle.bootstrapToggle('off');
            } else {
                toggle.bootstrapToggle('on');
            }
        });

        $('[data-toggle="admin-tooltip"]').tooltip({placement: '<?=$this->posTooltip;?>'});

        toggle.bootstrapToggle(isActiveToggle);
        toggle.change(function () {
            var state = History.getState();
            var isChecked = $(this).prop('checked');
            //console.log(state.url);
            History.pushState({edit_mode: isChecked}, $('title').text(), '?edit_mode=' + isChecked);
            // History.pushState({'edit_mode': isChecked}, null,state.hash);
            //location.reload();
            //$.fn.yiiGridView.update(grid_id);

        });
    });
</script>