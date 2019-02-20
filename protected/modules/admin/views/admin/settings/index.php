<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();



Yii::app()->clientScript->registerScript('admin-settings', "
    $(function(){
        var sel_list = '.field_site_close_text, .field_site_close_allowed_ip, .field_site_close_allowed_users';
        $('#SettingsAppForm_site_close').change(function(){
            common.hasChecked('#SettingsAppForm_site_close', sel_list);
        });
        common.hasChecked('#SettingsAppForm_site_close', sel_list);
    });
", CClientScript::POS_END);
?>
