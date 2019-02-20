<?php
Yii::import('mod.users.UsersModule');
 echo Html::openTag('ul');
if (Yii::app()->user->isGuest) {


    //$iconLogin = (isset($this->icon_login)) ? '<i class="' . $this->icon_login . '"></i>' : '';
    $iconLogin = '';
    echo Html::openTag('li');
    echo Html::ajaxLink($iconLogin . Yii::t('UsersModule.default', 'ENTER'), Yii::app()->createUrl('/users/login'), array(
        'type' => 'GET',
        'success' => "function( data ){
        var result = data;
        $('#login-form').dialog({
        model:true,
        // autoOpen: false,
        height: 'auto',
        title:'Авторизация',
        width: 420,
        modal: true,
        responsive: true,
        resizable: false,
        open:function(){
            $('#login-form').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                      login();
                }
            });
            $('.ui-widget-overlay').bind('click', function() {
                $('#login-form').dialog('close');
            });
            $('.ui-dialog :button').blur();
        },
        close:function(){
            $('#login-form').dialog('close');
        },
            buttons: [
                 /*       {
                text: '" . Yii::t('UsersModule.default', 'BTN_REGISTER') . "',
                'class':'btn btn-link',
                click: function() {
                   //
                }
            },*/
            {
                text: '" . Yii::t('UsersModule.default', 'BTN_LOGIN') . "',
                'class':'btn btn-default btn-signin',
                click: function() {
                    login();
                }
            }

            ]
        
        });
        $('#login-form').html(result); 
                $('.ui-dialog').position({
                  my: 'center',
                  at: 'center',
                  of: window,
                  collision: 'fit'
            });

        }",
        // 'data' => array('val1' => '1', 'val2' => '2'), // посылаем значения
        'cache' => 'false' // если нужно можно закэшировать
            ), array(// самое интересное
        // 'href' => Yii::app()->createUrl('ajax/new_link222'), // подменяет ссылку на другую
        'class' => 'top-signin', // добавляем какой-нить класс для оформления
        'id' => 'user-signin'
            )
    );
    echo Html::closeTag('li');
    if (Yii::app()->settings->get('users', 'registration')) {
        echo Html::openTag('li', array('class' => ''));
        $iconReg = (isset($this->icon_register)) ? '<i class="' . $this->icon_register . '"></i>' : '';
        echo Html::link($iconReg . Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register'), array('class' => ''));
        echo Html::closeTag('li');
    }
} else {
    ?>
    <li class="dropdown dropdown-small nav-user">
        <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><?= $this->username ?> <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><?= Html::link(Yii::t('app', 'PROFILE'), array('/users/profile')); ?></li>
            <li><?= Html::link(Yii::t('common', 'MY_ORDERS'), Yii::app()->createUrl('/cart/orders')); ?></li>
            <?php
            if (Yii::app()->user->isSuperuser) {

                echo Html::tag('li',array(),Html::link(Yii::t('app', 'ADMIN_PANEL'), array('/admin/')),true);

            }
            ?>
            <li><?= Html::link(Yii::t('app', 'LOGOUT'), Yii::app()->createUrl('/users/logout')); ?></li>
        </ul>
    </li>
    <?php
}
 echo Html::closeTag('ul');
?>

