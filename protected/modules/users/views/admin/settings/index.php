
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>
<?php if (Yii::app()->hasComponent('eauth')) { ?>
    <script type="text/javascript">
        $(function () {

            var objects = '<?= $model->getJsonSocialClasses() ?>';
            var socialAuth = '#SettingsUsersForm_social_auth';
            var json = $.parseJSON(objects);
            $.each(json, function (key, index) {
                hasChecked(key, index);
                $(key).click(function () {
                    $(index).each(function (key2, val) {
                        $(val).toggleClass('d-none');
                    });
                });

            });




           /* $('#SettingsUsersForm_social_auth2').click(function () {
                var that = this;
                $.each(json, function (key, index) {
                    //console.log(index);
                    if ($(that).attr('checked')) {
                        var t = $(key).parent().parent().parent().parent().removeClass('hidden2');
                        $(index).each(function (key2, val) {
                            $(key2).removeClass('d-none d-md-block d-xl-none');

                        });
                        // console.log('y');
                    } else {
                        $(key).parent().parent().parent().parent().addClass('hidden2');
                        $(index).each(function (key2, val) {
                            $(key2).addClass('d-none d-md-block d-xl-none');

                        });
                        //  console.log('n');
                    }
                    //console.log(index);

                    // $(key).toggleClass('hidden');
                    //$(val).toggleClass('hidden');
                });
            });
*/
            function hasChecked(has, array) {
                if ($(has).attr('checked')) {
                    $(array).each(function (key2, index2) {
                        $(index2).removeClass('d-none');
                    });
                } else {
                    console.log(array);
                    $(array).each(function (key2, index2) {
                        $(index2).addClass('d-none');
                    });
                }
            }

        });
    </script>
<?php } ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'docs',
    'options' => array(
        'title' => 'Документация',
        'autoOpen' => false,
        'modal' => true,
        'responsive' => true,
        'resizable' => false,
        'width' => '50%',
        'dialogClass' => 'no-padding',
        'draggable' => false,
    ),
));
?>
<div class="">
    <?php foreach ($model->tpl_remind() as $code) { ?>
        <div class="form-group row">
            <div class="col-sm-4"><code><?= $code ?></code></div>
            <div class="col-sm-8"><?= Yii::t('UsersModule.manual', $code) ?></div>
        </div>
    <?php } ?>
</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
