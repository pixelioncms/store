<div class="row">

    <div class="col-md-8">
        <h1><?= $this->pageName ?></h1>
        <?php
        
        
        //        'url' => Yii::app()->getModule('message')->inboxUrl,
        //'label' => 'Messages' .
       //     (Yii::app()->getModule('message')->getCountUnreadedMessages(Yii::app()->user->getId()) ?
      //          ' (' . Yii::app()->getModule('message')->getCountUnreadedMessages(Yii::app()->user->getId()) . ')' : ''),
      //  'visible' => !Yii::app()->user->isGuest),
  
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-profile-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data')
                ));
// echo CHtml::image($user->avatarPath, $user->username, array("width" => "50",'id'=>'user_avatar'))

        if ($user->hasErrors())
            Yii::app()->tpl->alert('danger', CHtml::errorSummary($user));
        ?>

        <div class="form-group">
            <?= $form->labelEx($user, 'username', array('class' => 'control-label')); ?>
            <?= $form->textField($user, 'username', array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'phone', array('class' => 'control-label')); ?>
<?php $this->widget('ext.inputmask.InputMask', array('model' => $user, 'attribute' => 'phone')); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'address', array('class' => 'control-label')); ?>
            <?= $form->textField($user, 'address', array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'subscribe', array('class' => 'control-label')); ?>
            <?= $form->checkBox($user, 'subscribe', array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'gender', array('class' => 'control-label')); ?>
            <?= $form->dropDownList($user, 'gender', $user->getSelectGender(), array('class' => 'form-control')); ?>
        </div>
        <?php
        if (Yii::app()->settings->get('users', 'change_theme')) {
            $themesNames = Yii::app()->themeManager->themeNames;

            $themes = array_combine($themesNames, $themesNames);
            if (count($themesNames) > 1) {
                unset($themes[Yii::app()->settings->get('app', 'theme')]);
            }
            ?>
            <div class="form-group">
                <?= $form->label($user, 'theme', array('class' => 'control-label')); ?>
                <?= $form->dropDownList($user, 'theme', $themes, array('class' => 'form-control', 'empty' => 'По умолчание')); ?>
            </div>
        <?php } ?>
        <?php if (Yii::app()->settings->get('users', 'upload_avatar')) { ?>
            <div class="form-group">
                <?= $form->labelEx($user, 'avatar', array('class' => 'control-label')); ?>
               
                <span class="btn btn-default btn-file">
         Выбрать
                <?= $form->fileField($user, 'avatar', array('class' => 'form-control file-caption')); ?>
                </span>
            </div>

        <?php } ?>
        <div class="text-center">
            <?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
    <div class="col-md-4">
        <img src="<?= $user->getAvatarUrl('150x150'); ?>" alt="" />
        <br/><br/>
        <div class="btn-group">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                Загрузить <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#"><?= Yii::t('UsersModule.default', 'LOAD_AVA_PC') ?></a></li>
                <li><a href="#"><?= Yii::t('UsersModule.default', 'LOAD_AVA_GAL') ?></a></li>
                <li><?php $this->widget('mod.users.widgets.webcam.Webcam'); ?></li>
            </ul>
        </div>
    </div>
</div>



<script>
    $(function() {
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
            console.log(numFiles);
            console.log(label);
        });
    });
</script>









<script type="text/javascript">

    function get_user_avatars(coll){
    
        $('body').append('<div id="dialog"></div>');
        $('#dialog').dialog({
            modal: true,
            resizable: false,
            width:'50%',
            open:function(){
                var that = this;
                $.ajax({
                    type:"POST",
                    data: {collection:coll},
                    url: '/users/profile/getAvatars',
                    success: function(result){
                        $(that).html(result);
                    }
                });
                
            },
            close: function (event, ui) {
                $(this).remove();
            },
            buttons:[{
                    text:'Сохранить',
                    click:function(){

                        var that = this;
                      
                        var image = $('#selected_avatar').val();
                        $.ajax({
                            type:"POST",
                            data: {img:image},
                            url: '/users/profile/saveAvatar',
                            success: function(result){
                                console.log(image);
                                $('#user_avatar').attr('src',image);
                                $.jGrowl('Аватар успешно установлен.', {themeState:'none'});
                                $(that).dialog('close');
                            }
                        });

                    }
                },{
                    text:'Отмена',
                    click:function(){
                        $(this).dialog('close');
                    }
                }]
                        
        });
    }
</script>