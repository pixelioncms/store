



<?php

$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => $tabs,/*array(
        'Профиль' => array(
            'content' => $this->renderPartial('_profile', array('user' => $user, 'changePasswordForm' => $changePasswordForm), true),
            'id' => 'profile'
        ),
        'Изменить пароль' => array(
            'content' => $this->renderPartial('_changepass', array('user' => $user, 'changePasswordForm' => $changePasswordForm), true),
            'id' => 'changepass'
        ),
        'AjaxTab' => array('ajax' => $ajaxUrl),
    ),*/
    'options' => array(
        'collapsible' => true,
        "activate" => 'js:function(event, ui){
             window.location.hash = ui.newTab.find("a").attr("href");
             $(document).scrollTop(0);
             }'
    ),
    'htmlOptions' => array(
        'class' => 'cassess'
    )
));
?>









