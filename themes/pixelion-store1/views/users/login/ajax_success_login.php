<script>
    location.reload();
    $(function () {
        $('.ui-dialog-buttonpane').remove();

    });
</script>
<?php
echo Yii::app()->tpl->alert('success', Yii::t('UsersModule.default', 'LOGIN_SUCCESS'));
?>
