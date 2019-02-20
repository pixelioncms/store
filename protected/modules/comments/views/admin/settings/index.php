<script type="text/javascript">
    $(function(){
        $('#SettingsUsersForm_upload_types').tagsInput({width:'100%','defaultText':'Добавить формат'});
        $('#SettingsUsersForm_bad_name').tagsInput({width:'100%','defaultText':'Добавить Имя'});
        $('#SettingsUsersForm_bad_email').tagsInput({width:'100%','defaultText':'Добавить сервис'});
    });
</script>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

echo $model->getForm();
Yii::app()->tpl->closeWidget();



