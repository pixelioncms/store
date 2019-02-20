<?php

class AvatarAction extends CAction {

    public function run() {
        $collection = $_POST['collection'];
        $avatars = array();
        if (!Yii::app()->user->isGuest && $collection) {


            $dir = opendir(Yii::getPathOfAlias('webroot.uploads.users.avatars') . DS . $collection);
            while ($entry = readdir($dir)) {
                if (preg_match("/(\.gif|\.png|\.jpg|\.jpeg)$/is", $entry) && $entry != "." && $entry != "..") {
                    //  $entryname = str_replace("_", " ", preg_replace("/^(.*)\..*$/", "\\1", $entry));
                    $avatars[] = $entry;
                }
            }
            closedir($dir);


            $this->controller->render('_avatar_collections', array('avatars' => $avatars, 'collection' => $collection));
        } else {
            //redirect
        }
    }

}
