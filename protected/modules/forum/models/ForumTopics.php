<?php

class ForumTopics extends ActiveRecord {

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';

    public $text;

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        Yii::import('ext.tageditor.TagEditor');
        Yii::import('ext.tinymce.TinymceArea');
        Yii::import('ext.bootstrap.fileinput.FileInput');
        return array(
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CONTENT'),
                    'elements' => array(
                        'title' => array(
                            'type' => 'text',
                            'id' => 'title'
                        ),
                        'seo_alias' => array(
                            'type' => 'text',
                            'id' => 'alias',
                            'visible' => (Yii::app()->settings->get('app', 'translate_object_url')) ? false : true
                        ),
                        'short_text' => array(
                            'type' => 'TinymceArea',
                        ),
                        'full_text' => array(
                            'type' => 'TinymceArea',
                        ),
                        'tags' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                            //'defaultText'=>'lala'
                            )
                        ),
                    ),
                ),
                'additional' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_ADDITIONALLY'),
                    'elements' => array(
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => array(0 => Yii::t('app', 'OFF', 0), 1 => Yii::t('app', 'ON', 0))
                        ),
                        'date_create' => array(
                            'type' => 'CJuiDatePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                                'style' => 'width:150px;',
                                'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                            )
                        ),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        );
    }

    public function getGridColumns() {
        return array(
            array(
                'name' => 'title',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'text-left'),
                'value' => 'Html::link(Html::encode($data->title),"/forum/topic/1/$data->seo_alias", array("target"=>"_blank"))',
            ),
            array(
                'name' => 'user_id',
                'type' => 'raw',
                'value' => 'CMS::userLink($data->user)',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'views',
                'value' => '$data->views',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'rating',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'text-center'),
                'value' => 'CMS::vote_graphic($data->score,$data->rating)',
            ),
            array(
                'name' => 'date_create',
                'value' => 'CMS::date($data->date_create)',
            ),
            array(
                'name' => 'date_update',
                'value' => 'CMS::date($data->date_update)',
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn'),
                array('class' => 'ext.sortable.SortableColumn')
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Page the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{forum_topics}}';
    }

    public function scopes() {
        return CMap::mergeArray(array(
                    'latast' => array(
                        'order' => 'date_create DESC'
                    ),
                        ), parent::scopes());
    }

    public function getUrl() {
        return Yii::app()->createUrl('/forum/topics/view', array('id' => $this->id));
    }

    /**
     * Find news by url.
     * Scope.
     * @param string News url
     * @return News
     */
    public function withUrl($url) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $url)
        ));

        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('title, text', 'type', 'type' => 'string'),
            array('title, text', 'length', 'min' => 3),
            array('title, text', 'required'),
            array('is_close', 'boolean'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('title', 'length', 'max' => 140),
            array('id, user_id, title, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            //  'topicsCount' => array(self::STAT, 'ForumTopics', 'id'),
            'postsCount' => array(self::STAT, 'ForumPosts', 'topic_id'),
            'posts' => array(self::HAS_MANY, 'ForumPosts', 'topic_id', 'order' => '`posts`.`id` ASC'),
            'postsDesc' => array(self::HAS_MANY, 'ForumPosts', 'topic_id', 'order' => '`postsDesc`.`id` DESC'),
            'category' => array(self::BELONGS_TO, 'ForumCategories', 'category_id'),
            'postLast' => array(self::BELONGS_TO, 'ForumPosts', 'last_post_id'),
                //'posts' => array(self::HAS_MANY, 'ForumPosts', 'topic_id', 'order'=>'`posts`.`date_create` DESC'),
                //'parent' => array(self::BELONGS_TO, 'ForumTopics', 'parent_id'),
                //'parents' => array(self::HAS_MANY, 'ForumTopics', 'parent_id'),
                //'parentsCount' => array(self::STAT, 'ForumTopics', 'parent_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();
        if (Yii::app()->hasModule('comments')) {
            Yii::import('mod.comments.models.Comments');
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'model' => 'mod.shop.models.ShopProduct',
                'owner_title' => 'title', // Attribute name to present comment owner in admin panel
            );
        }
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('date_create', 'date_update'),
        );

        return CMap::mergeArray($a, parent::behaviors());
    }

    public static function getCSort() {
        $sort = new CSort;
        // $sort->defaultOrder = 't.ordern DESC';
        $sort->attributes = array(
            '*',
            'title' => array(
                'asc' => 'translate.title',
                'desc' => 'translate.title DESC',
            )
        );

        return $sort;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions. Used in admin search.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('user');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('t.switch', $this->switch);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => self::getCSort(),
            'pagination' => array('pageVar' => 'page'/* ,'route'=>'/news' */)
        ));
    }

}
