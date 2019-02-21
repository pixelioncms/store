<?php

class ForumModule extends WebModule
{

    public $edit_mode = true;
    public $_addonsArray;

    public $configFiles = array(
        'forum' => 'SettingsForumForm'
    );

    public function init()
    {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
            $this->id . '.components.tinymce.*',
        ));

        Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum.css");
        Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/forum-data.css");
        Yii::app()->clientScript->registerScriptFile($this->assetsUrl . "/forum.js", CClientScript::POS_END);

        $this->setIcon('icon-comments');
    }

    public function afterInstall()
    {
        Yii::app()->cache->flush(); //Чистим кеш, нужно для авто добавление ячейки в таблицу пользователя. см. ниже.

        if (!isset(Yii::app()->db->schema->getTable(User::model()->tableName())->columns['forum_posts_count'])) {
            Yii::app()->db->createCommand()->addColumn(User::model()->tableName(), 'forum_posts_count', 'int(11) NOT NULL DEFAULT "0"');
        }
        return parent::afterInstall();
    }

    public function afterUninstall()
    {
        Yii::app()->cache->flush(); //Чистим кеш, нужно для авто добавление ячейки в таблицу пользователя. см. ниже.

        if (isset(Yii::app()->db->schema->getTable(User::model()->tableName())->columns['forum_posts_count'])) {
            Yii::app()->db->createCommand()->dropColumn(User::model()->tableName(), 'forum_posts_count');
        }

        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(ForumCategories::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(ForumPosts::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(ForumTopics::model()->tableName());

        return parent::afterUninstall();
    }

    public function getRules()
    {
        return array(
            'forum' => 'forum/default/index',
            'forum/quote/*' => 'forum/default/quote',

            'forum/category/<id>' => 'forum/default/view',
            'forum/editpost/<id>' => 'forum/topics/editpost',

            'forum/category/<id>/addtopic' => 'forum/topics/addTopic',
            'forum/topic/addreply' => 'forum/topics/addreply',
            'forum/topic/<id>/*' => 'forum/topics/view',
            'forum/topic/<id>' => 'forum/topics/view',
            'forum/addcat/<parent_id>' => 'forum/default/addCat',
        );
    }

    public function getAdminMenu()
    {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => $this->getIsActive('admin/forum'),
                        'visible' => Yii::app()->user->checkAccess('Forum.Default.*'),
                    ),
                ),
            ),
        );
    }

}
