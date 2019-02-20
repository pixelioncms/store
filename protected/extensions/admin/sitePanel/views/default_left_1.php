<?php
$dataModel = Yii::app()->controller->adminModel();
?>
<script>
    $(function () {
        $('#pnl').click(function () {
            $('#admin-panel').toggleClass('open');
            $('#collapseExample').toggleClass('hidden');
        });
    });
</script>
<div id="admin-panel" class="ap ap-fixed-left">
    <ul class="nav">
        <li id="panel2">
            <a id="pnl" href="javascript:void(0)" data-toggle="collapse2" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span class="ap-favicon"></span></a>
        </li>
    </ul>
    <ul class="nav navbar-right">
        <li class="dropdown"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="javascript:void(0)">Добро пожаловать <?= Html::image(Yii::app()->user->getAvatarUrl('25x25'), Yii::app()->user->username, array("width" => "16", "height" => "16", 'class' => 'user-avatar')) ?> <?= Yii::app()->user->username ?></a>
            <ul class="dropdown-menu">
                <li><?= Html::link(Yii::t('app', 'UPDATE', 0), array('/admin/users/default/update', 'id' => Yii::app()->user->id)) ?></li>
                <li><?= Html::link(Yii::t('app', 'LOGOUT'), array('/admin/auth/logout')) ?></li>
                <?php //$this->widget('ext.blocks.chooseLanguage.ChooseLanguage', array('skin' => 'flag'));  ?>

            </ul>
        </li> 
    </ul>
    <div class="clearfix"></div>
    <div class="collapse2 hidden" id="collapseExample">
        <div class="btn-group-wrap">
            <div class="ap-btn-group text-center">
                <a href="#" class="ap-btn ap-btn-success"><i class="icon-add"></i></a>
                <a href="#" class="ap-btn ap-btn-warning"><i class="icon-edit"></i></a>

            </div>

        </div>
        <?php
        echo Html::checkBox('edit_mode', Yii::app()->request->getParam('edit_mode') ? true : false, array(
            'data-toggle' => 'toggle',
            'data-on' => "On",
            'data-off' => "Off",
            'data-offstyle' => 'info',
            'data-onstyle' => 'success',
                //'data-size' => 'mini',
        ));
        ?>


        Режим редактирование
        <div class="clearfix"></div>
        <ul class="ap-nav">
            <?php foreach ($menu as $row) { ?>
                <?php if (isset($row['items'])) { ?>
                    <li <?php echo (isset($row['items'])) ? 'class="dropdown "' : 'class="panelObj"'; ?>>
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" href="<?php echo CHtml::normalizeUrl($row['url']) ?>">
                            <i class="<?= $row['icon'] ?>"></i>
                            <?= $row['label'] ?>
                            <?php if (isset($row['items'])) { ?>
                                <span class="ap-arrow ap-arrow-right"></span>
                            <?php } ?>
                        </a>
                        <?php if (isset($row['items'])) { ?>
                            <ul class="ap-dropdown-menu ap-dropdown-menu-right">
                                <?php foreach ($row['items'] as $rowItems) { ?>
                                    <?php if ($rowItems['visible'] == true) { ?>
                                        <li>
                                            <a href="<?php echo CHtml::normalizeUrl($rowItems['url']) ?>"><i class="<?= $rowItems['icon'] ?>"></i><?= $rowItems['label'] ?></a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        <?php } ?>

                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>
