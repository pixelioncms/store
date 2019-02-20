<?php

Yii::import('mod.news.models.NewsTranslate');

class News extends ActiveRecord {

    const MODULE_ID = 'news';
    const route = '/news/admin/default';

    public $tags;

    /**
     * Multilingual attrs
     */
    public $title;
    public $short_text;
    public $full_text;
    public $enableAttachment = true;
    // public $files;

    /**
     * Name of the translations model.
     */
    public $translateModelName = 'NewsTranslate';

    public function getForm() {
        Yii::import('app.jui.JuiDatePicker');
        Yii::import('ext.tageditor.TagEditor');
        Yii::import('ext.tinymce.TinymceArea');
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
                    /* Yii::app()->controller->widget('ext.attachment.AttachmentWidget', array(
                      'model' => $this
                      ), true), */
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
                            'type' => 'JuiDatePicker',
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
                /*  'ajaxSubmit' => array(
                  'type' => 'ajaxSubmit',
                  'update' => '#ss' . __CLASS__,
                  'dataType' => 'json',
                  'class' => 'btn btn-success',
                  'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE'),
                  'params' => array(
                  'ajaxUrl' => '/admin/news/default/update?id=3'
                  )
                  ), */
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        );
    }

    public function getGridColumns() {
        $columns = array();
        $columns[] = array(
            'name' => 'title',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-left'),
            'value' => 'Html::link(Html::encode($data->title),"/news/$data->seo_alias", array("target"=>"_blank"))',
        );
        $columns[] = array(
            'name' => 'user_id',
            'type' => 'raw',
            'value' => 'CMS::userLink($data->user)',
            'htmlOptions' => array('class' => 'text-center')
        );
        $columns[] = array(
            'name' => 'views',
            'filter' => false,
            'value' => '$data->views',
            'htmlOptions' => array('class' => 'text-center')
        );
        $columns[] = array(
            'name' => 'rating',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'CMS::vote_graphic($data->score,$data->rating)',
        );
        $columns[] = array(
            'name' => 'date_create',
            'value' => 'CMS::date($data->date_create)',
        );
        $columns[] = array(
            'name' => 'date_update',
            'value' => 'CMS::date($data->date_update)',
        );
        $columns['DEFAULT_CONTROL'] = array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        );

        $columns['DEFAULT_COLUMNS'][] = array('class' => 'CheckBoxColumn');
        if (Yii::app()->user->openAccess(array("News.Default.*", "News.Default.Sortable"))) {
            $columns['DEFAULT_COLUMNS'][] = array('class' => 'ext.sortable.SortableColumn');
        }

        return $columns;
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
        return '{{news}}';
    }

    public function scopes() {
        return CMap::mergeArray(array(
                    'latast' => array(
                        'order' => 'date_create DESC'
                    ),
                        ), parent::scopes());
    }

    public function getUrl() {
        return Yii::app()->createUrl('/news/default/view', array('seo_alias' => $this->seo_alias));
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
            array('short_text, full_text, tags', 'type', 'type' => 'string'),
            array('title, full_text', 'length', 'min' => 3),
            /*  array('files', 'FileValidator', 'types' => 'jpg, gif, png',
              'allowEmpty' => true,
              //   'safe' => true,
              'maxSize' => 1024 * 1024 * 50,
              'tooLarge' => 'File has to be smaller than 50MB'
              ), */
            array('title', 'required'),
            array('title', 'StripTagsValidator'),
            array('seo_alias', 'TranslitValidator'),
            array('seo_alias', 'required'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('seo_alias', 'length', 'max' => 255),
            array('title', 'length', 'max' => 140),
            array('id, user_id, title, seo_alias, views, short_text, full_text, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return CMap::mergeArray(array(
                    'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
                    'user' => array(self::BELONGS_TO, 'User', 'user_id'),
                        ), parent::relations());
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();

        $a['tags'] = array(
            'class' => 'app.behaviors.TagsBehavior',
            //'tags'=>$this->tags,
            'router' => '/news/default/index'
        );
        $a['seo'] = array(
            'class' => 'mod.seo.components.SeoBehavior',
            'url' => $this->getUrl()
        );
        $a['timeline'] = array(
            'class' => 'app.behaviors.TimelineBehavior',
            'attributes' => 'title',
        );
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


        $a['favirites'] = array(
            'class' => 'mod.users.components.FavoritesBehavior',
            'model' => 'mod.news.models.News',
            'owner_title' => 'title',
        );
        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'translateAttributes' => array(
                'title',
                'short_text',
                'full_text',
            ),
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

        $criteria->with = array('user', 'translate');

        if (isset($_GET['tag']))
            $criteria->addSearchCondition('tags', $_GET['tag']);

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('translate.title', $this->title, true);
        $criteria->compare('translate.full_text', $this->full_text, true);
        $criteria->compare('translate.short_text', $this->short_text, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => self::getCSort(),
            'pagination' => array('pageVar' => 'page'/* ,'route'=>'/news' */)
        ));
    }

}
