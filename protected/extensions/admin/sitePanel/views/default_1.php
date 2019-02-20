<?php
$dataModel = Yii::app()->controller->adminModel();
?>

<div id="admin-panel" class="ap ap-fixed-top">

    <ul class="ap-nav">
        <li id="panel"><a href="javascript:void(0)"><i class="ap-favicon"></i></a></li> 
        <?php foreach ($menu as $row) { ?>
            <?php if (isset($row['items'])) { ?>
                <li <?php echo (isset($row['items'])) ? 'class="dropdown "' : 'class="panelObj"'; ?>>
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="<?php echo CHtml::normalizeUrl($row['url']) ?>">
                        <?= $row['label'] ?></a>
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
        <li>


            <?php
            echo Html::checkBox('edit_mode', Yii::app()->request->getParam('edit_mode') ? true : false, array(
                'data-toggle' => 'toggle',
                'data-on' => "On",
                'data-off' => "Off",
                'data-offstyle' => 'danger',
                'data-onstyle' => 'success',
                'data-size' => 'mini',
                'data-height' => '22',
            ));
            ?>


            Режим редактирование


        </li>

        <?php
        if ($dataModel->scenario == 'update') {
            echo '<li>';
            //Yii::t(ucfirst($dataModel::MODULE_ID).'Module.default','UPDATE')
            echo Html::link('<i class="icon-add text-success"></i>', $dataModel->getCreateUrl(), array(
                'title'=>Yii::t(ucfirst($dataModel::MODULE_ID).'Module.default','CREATE'),
                'target' => '_blank',
                'data-toggle'=>'admin-tooltip',
                'class'=>'ap-btn2 ap-btn-success2'
                ));
            echo '</li>';
        }
        ?>


        <?php
        if ($dataModel->scenario == 'update') {

            echo '<li>';
            echo Html::link('<i class="icon-edit"></i>', $dataModel->getUpdateUrl(), array(
                                'title'=>Yii::t('app','UPDATE',0),
                'target' => '_blank',
                  'data-toggle'=>'admin-tooltip',

                ));
            echo '</li>';
        }
        ?>

    </ul>
    <ul class="nav  navbar-right">
        <li class="dropdown"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="javascript:void(0)">Добро пожаловать <?= Html::image(Yii::app()->user->getAvatarUrl('25x25'), Yii::app()->user->username, array("width" => "16", "height" => "16", 'class' => 'user-avatar')) ?> <?= Yii::app()->user->username ?></a>
            <ul class="dropdown-menu">
                <li><?= Html::link(Yii::t('app', 'UPDATE', 0), array('/admin/users/default/update', 'id' => Yii::app()->user->id)) ?></li>
                <li><?= Html::link(Yii::t('app', 'LOGOUT'), array('/admin/auth/logout')) ?></li>
                <?php //$this->widget('ext.blocks.chooseLanguage.ChooseLanguage', array('skin' => 'flag'));  ?>

            </ul>
        </li> 
    </ul>
    <?php
//print_r($_SESSION);
//echo Yii::app()->session['edit_mode'];
    ?>


</div>

<script>
    var edit_mode_session = <?= ($_SESSION['edit_mode']) ? $_SESSION['edit_mode'] : 0 ?>;
    var isActiveToggle = (edit_mode_session) ? 'on' : 'off';
    var toggle = $('#edit_mode');



    $(document).ready(function () {
          $('[data-toggle="admin-tooltip"]').tooltip({placement:'bottom'});
        toggle.bootstrapToggle(isActiveToggle);
        toggle.change(function () {
            var state = History.getState();
            var isChecked = $(this).prop('checked');
            //console.log(state.url);
            History.pushState({edit_mode: isChecked}, null, '?edit_mode=' + isChecked);
            // History.pushState({'edit_mode': isChecked}, null,state.hash);
            location.reload();
            //$.fn.yiiGridView.update(grid_id);

        });
    });
</script>