<script type="text/javascript">
    $(function () {
        $('.delete-widget').click(function () {
            var uri = $(this).attr('href');
            var ids = $(this).attr('data-id');

            common.ajax(uri, {}, function (data) {
                $('#ids_' + ids).remove();
                common.notify('<?= Yii::t('app', 'SUCCESS_RECORD_DELETE') ?>', 'success');
                common.removeLoader();
            });
            return false;
        });

        $('#createWidget').click(function () {
            var uri = $(this).attr('href');
            common.ajax(uri, {}, function (data) {

                $('body').append('<div id="dialog" class="no-padding"></div>');

                $('#dialog').dialog({
                    modal: true,
                    autoOpen: true,
                    width: 500,
                    title: "<?= Yii::t('app', 'DESKTOP_CREATE_WIDGET'); ?>",
                    resizable: false,
                    open: function () {
                        //var obj = $.parseJSON(data);
                        $(this).html(data); //obj.content
                        common.removeLoader();
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    },
                    buttons: [{
                        text: common.message.save,
                        'class': 'btn btn-sm btn-success',
                        click: function () {
                            var str = $('#dialog form').serialize();
                            str += '&json=true';
                            common.ajax(uri, str, function (data) {
                                console.log(data);
                                //var obj = $.parseJSON(data);
                                // var obj = data;
                                //if (obj.success) {
                                $('#dialog').dialog('close');
                                location.reload();
                                //} else {
                                //  common.notify(obj.message, 'error');
                                //$('#dialog').html(data); //obj.content
                                //}
                            });
                        }
                    }, {
                        text: common.message.cancel,
                        'class': 'btn btn-sm btn-secondary',
                        click: function () {
                            $(this).dialog('close');
                        }
                    }]
                });
                $("#dialog").dialog("open");
            });
            return false;
        });

        $(".column").sortable({
            cursor: 'move',
            connectWith: ".column",
            handle: ".handle",
            revert: true, //animation
            placeholder: "placeholder",
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                data += '&column_new=' + $(this).attr('data-id');
                data += '&desktop_id=' + $(this).attr('data-desktop-id');
                $.post('/admin/desktop/sortable', data, function(){
                    common.notify('Success','success');
                });

            }
        }).disableSelection();
    });


</script>

<div class="row no-gutters2 d-none">
    <div class="col-sm">
        <div class="card bg-success text-white">
            <div class="card-body" style="padding: 1rem">
                <div class="row">
                    <div class="col-sm-5">
                        <i class="icon-users icon-x5"></i>
                    </div>
                    <div class="col-sm-7 text-right">
                        <div class="huge">26</div>
                        <div>Новых пользователей</div>
                    </div>
                </div>
            </div>
            <a href="#" class="card-footer">
                <span class="float-left">Подробней</span>
                <span class="float-right"><i class="icon-arrow-right"></i></span>
            </a>
        </div>
    </div>
    <div class="col-sm">
        <div class="card bg-primary text-white">
            <div class="card-body" style="padding: 1rem">
                <div class="row">
                    <div class="col-sm-5">
                        <i class="icon-stats icon-x5"></i>
                    </div>
                    <div class="col-sm-7 text-right">
                        <div class="huge">26</div>
                        <div>Новых пользователей</div>
                    </div>
                </div>
            </div>
            <a href="#" class="card-footer">
                <span class="float-left">Подробней</span>
                <span class="float-right"><i class="icon-arrow-right"></i></span>
            </a>
        </div>
    </div>
    <div class="col-sm">
        <div class="card bg-danger text-white">
            <div class="card-body" style="padding: 1rem">
                <div class="row">
                    <div class="col-sm-5">
                        <i class="icon-shopcart icon-x5"></i>
                    </div>
                    <div class="col-sm-7 text-right">
                        <div class="huge">26</div>
                        <div>Заказов на <b>30 400</b> <sup>грн.</sup></div>
                    </div>
                </div>
            </div>
            <a href="#" class="card-footer">
                <span class="float-left">Подробней</span>
                <span class="float-right"><i class="icon-arrow-right"></i></span>
            </a>
        </div>
    </div>
    <div class="col-sm">
        <div class="card bg-info text-white">
            <div class="card-body" style="padding: 1rem">
                <div class="row">
                    <div class="col-sm-5">
                        <i class="icon-users icon-x5"></i>
                    </div>
                    <div class="col-sm-7 text-right">
                        <div class="huge">26</div>
                        <div>Новых пользователей</div>
                    </div>
                </div>
            </div>
            <a href="#" class="card-footer">
                <span class="float-left">Подробней</span>
                <span class="float-right"><i class="icon-arrow-right"></i></span>
            </a>
        </div>
    </div>
</div>


<?php


if (isset($desktop) && isset($AddonsItems)) {
    if ($desktop->addons) {
        ?>
        <div class="row">
            <?php
            foreach ($AddonsItems as $key => $item) {
                if (isset($item['count'])) {
                    $badge = '<span class="badge badge-success">' . $item['count'] . '</span>';
                } else {
                    $badge = '';
                }
                $visible = (isset($item['visible'])) ? $item['visible'] : true;
                if ($visible) {
                    $html = $badge . $item['icon'];
                    $html .= '<h4>' . $item['label'] . '</h4>';
                    ?>
                    <div class="col-xs-12 col-md-2 col-sm-4 col-lg-2 main-icon">
                        <?= Html::link($html, $item['url'], array('class' => 'small-thumbnail text-center')); ?>


                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
}
?>


<div class="row desktop">
    <?php
    Yii::import('app.blocks_settings.*');
    $manager = new WidgetSystemManager;
    $x = 0;
    if ($desktop->columns) {
        while ($x++ < $desktop->columns) {
            if ($desktop->columns == 3) {
                $class = 'col-lg-4 col-md-4';
            } elseif ($desktop->columns == 2) {
                $class = 'col-lg-6 col-md-4';
            } else {
                $class = '';
            }
            ?>
            <div class="column <?= $class; ?>" data-id="<?= $x; ?>" data-desktop-id="<?= $desktop->id ?>">&nbsp;
                <?php
                $cr = new CDbCriteria;

                $cr->condition = '`t`.`column`=:clmn AND `t`.`desktop_id`=:desktopID';
                $cr->order = '`t`.`ordern` DESC';
                $cr->params = array(
                    ':clmn' => $x,
                    ':desktopID' => $desktop->id
                );
                $widgets = DesktopWidgets::model()
                    ->findAll($cr);
                if ($widgets) {
                    foreach ($widgets as $wgt) {
                        ?>
                        <div class="card bg-light desktop-widget" id="ids_<?= $wgt->id ?>" data-test="test-<?= $x ?>">
                            <div class="card-header">
                                <h5>
                                    <?= $manager->getWidgetTitle($wgt->widget_id); ?>
                                </h5>
                                <div class="card-option">
                                    <?php
                                    $system = $manager->getSystemClass($wgt->widget_id);
                                    if ($system) {
                                        echo Html::link('<i class="icon-settings"></i>', $this->createUrl('/admin/app/widgets/update', array('alias' => $wgt->widget_id)), array('class' => ' btn btn-link'));
                                    }

                                    echo Html::link('<i class="icon-move"></i>', '#', array('class' => 'handle btn btn-link'));
                                    if (Yii::app()->user->checkAccess('Admin.Desktop.*') || Yii::app()->user->checkAccess('Admin.Desktop.DeleteWidget')) {
                                        echo Html::link('<i class="icon-delete"></i>', $this->createUrl('desktop/deleteWidget', array('id' => $wgt->id)), array('data-id' => $wgt->id, 'class' => 'delete-widget btn btn-link'));
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php $this->widget($wgt->widget_id) ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>

