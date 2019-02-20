<?php
/**
 * @var UserProfile $profile
 * @var User $user
 * @var Controller $this
 */
$this->pageTitle = Yii::t('UsersModule.site', 'Profile '.$user->username);
?>

<h2><?php echo Yii::t('UsersModule.site', 'Profile '.$user->username) ?></h2>
<?php
$label = $user->attributeLabels();
  
?>
<?php if(!$user->banned){ ?>
<table border="1" width="100%">

        <tr>
        <td><?php echo ($label['username']);?></td>
        <td><?= $user->username;?></td>
    </tr>
    <tr>
        <td><?php echo ($label['email']);?></td>
        <td><?= $user->email;?></td>
    </tr>
    <tr>
        <td><?php echo ($label['date_registration']);?></td>
        <td><?= CMS::date($user->date_registration);?></td>
    </tr>

    <tr>
        <td><?php echo ($label['last_login']);?></td>
        <td><?= CMS::date($user->last_login);?></td>
    </tr>
</table>

<?php }else{
    
    Yii::app()->tpl->alert('info','Пользователь заблокирован');
}

if(Yii::app()->user->getIsSuperuser()){

    
}