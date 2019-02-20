<?php

/**
 * PanelWidget 
 * 
 * @property array $menu Массив меню
 * @uses CWidget
 * @access superuser aka Admin
 */
class PanelWidget extends CWidget {
    public static function actions() {
        return array(
            'action' => 'ext.admin.sitePanel.actions.AdminPanelAction',
        );
    }
    public $pos = 'top'; //default is top
    public $menu = array();
    private $posArray = array('top', 'bottom', 'left', 'right');
    public $posTooltip;
    public $countOrder = 0;
    public $countComments = 0;

    public function init() {
        if ($this->pos == 'top') {
            $this->posTooltip = 'bottom';
        } elseif ($this->pos == 'bottom') {
            $this->posTooltip = 'top';
        }
        if(Yii::app()->hasModule('cart')){
            Yii::import('mod.cart.models.Order');
            $this->countOrder = Order::model()->new()->count();
        }
        if(Yii::app()->hasModule('comments')){
            Yii::import('mod.comments.models.Comments');
            $this->countComments = Comments::model()->new()->count();
        }
        if (Yii::app()->user->isSuperuser)
            $this->registerScripts();
    }

    public function run() {
        if (Yii::app()->user->isSuperuser) {
       
            if(isset(Yii::app()->getModule('admin')->adminMenu)){
                $appMenu = Yii::app()->getModule('admin')->adminMenu['system']['items'];
            }else{
                $appMenu=array();
            }
            Yii::import('mod.admin.widgets.EngineMainMenu');
            $modules2 = new EngineMainMenu;
            $modules = $modules2->findMenu();
            $this->menu = array(
                array(
                    'label' => Yii::t('app', 'SYSTEM'),
                    'url' => 'javascript:void(0)',
                    'icon' => Html::icon('icon-tools'),
                    'items' => $appMenu
                ),
                array(
                    'label' => Yii::t('app', 'MODULES'),
                    'url' => 'javascript:void(0)',
                    'icon' => Html::icon('icon-puzzle'),
                    'items' => $modules['modules']['items']
                ),
                //$modules['orders'],
                //$modules['shop']
            );
            if(Yii::app()->hasModule('cart')){
                $this->menu[]=$modules['orders'];
            }
            if(Yii::app()->hasModule('shop')){
                $this->menu[]=$modules['shop'];
            }
            $this->render($this->getMySkin(), array(
                'menu' => $this->menu,
                'countOrder' => $this->countOrder,
                'countComments' => $this->countComments,
            ));
        }
    }

    private function getMySkin() {
        if ($this->pos == 'right' || $this->pos == 'left') {
            return $this->skin . '_left-right';
        } else {
            return $this->skin;
        }
    }

    private function registerScripts() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('cookie');
        $cs->registerScriptFile($assetsUrl . '/admin_panel.js',CClientScript::POS_END);

        $cs->registerCssFile($assetsUrl . "/ap-bootstrap.css");
        $cs->registerCssFile($assetsUrl . '/admin_panel.css');


        //if (Yii::app()->user->isEditMode) {
            $assetsTinymceUrl = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('ext.tinymce.assets'), false, -1, YII_DEBUG
            );


            $cs->registerScriptFile($assetsTinymceUrl . '/tinymce.min.js', CClientScript::POS_END);

            //$this->widget('ext.tinymce.TinymceWidget');
            $cs->registerCssFile($assetsUrl . '/edit_mode.css');

            $cs->registerScript('el', "
function tinymce_ajax(obj){
    var form = obj.formElement;

    var str = $(form).serialize();
    str+='&edit_mode=1&redirect=0';

    $.ajax({
        type:$(form).attr('method'),
        url:$(form).attr('action'),
        data:str,
        dataType:'json',
        beforeSend:function(){
            obj.setProgressState(true);
        },
        success: function(response){
            if(response.errors !== undefined){
                $.each(response.errors, function (key, data) {
                    common.notify(data,'error');
                });
            }else{
                 common.notify(response.message,'success');
            }
            obj.setProgressState(false);
        },
        error:function(jqXHR, textStatus, errorThrown){
            console.log(textStatus);
            console.log(jqXHR);
            common.notify('Ошабка: ','error');
            obj.setProgressState(false);
        }
    });
}
");

            /* $cs->registerScript('adminwidgetpanel', "

function progressState(obj,bool){
     obj.setProgressState(bool);
}
tinymce.init({
    selector: '.edit_mode_title',
    language : common.language,
    inline: true,
    width : 100,
    plugins: 'save',
    toolbar: 'save undo redo',
    menubar: false,
    toolbar_items_size: 'small',
    save_enablewhendirty: true,
    save_onsavecallback: function() {
        console.log(this);
        tinymce_ajax(this);
    },
    content_css : '" . Yii::app()->controller->getAssetsUrl() . "/css/tinymce.css'
});

tinymce.init({
    selector: '.edit_mode_text',
    language : common.language,
    inline: true,
    width : 200,
    plugins: 'save',
    toolbar: 'save undo redo | styleselect',
    menubar: false,
    toolbar_items_size: 'small',
    save_onsavecallback: function() {
        console.log(this);
        tinymce_ajax(this);
    },
    content_css : '" . Yii::app()->controller->getAssetsUrl() . "/css/tinymce.css'

});


                    ");*/
        //}
    }

}
