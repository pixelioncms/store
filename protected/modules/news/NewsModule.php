<?php

class NewsModule extends WebModule {

    public $edit_mode = true;
    public $_addonsArray;

    public $configFiles = array(
        'news' => 'SettingsNewsForm'
    );

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
        $this->setIcon('icon-newspaper');
    }



    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(News::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(NewsTranslate::model()->tableName());

        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'news' => 'news/default/index',
            // 'news/append/<append>/page/<page>' => 'news/default/index',
            'news/<seo_alias>' => 'news/default/view',
            // 'news/category/<category>' => 'news/default/index',
            'news/default/upload' => 'news/default/upload',
                /* 'news/<tag:.*?>' => 'news/default/index', */
                //    'news/page/<page:\d+>' => 'news/default/index', 
        );
    }



    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => $this->getIsActive('admin/news'),
                        'visible' => Yii::app()->user->openAccess(array('News.Default.*', 'News.Default.Index')),
                    ),
                ),
            ),
        );
    }

    public function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('NewsModule.default', 'CREATE'),
                    'url' => '/admin/news/default/create',
                    'icon' => Html::icon(Yii::app()->getModule('news')->icon, array('class' => 'icon-x4 display-block')),
                    'visible' => Yii::app()->user->openAccess(array('News.Default.*','News.Default.Update'))
                )
            )
        );
    }

}
