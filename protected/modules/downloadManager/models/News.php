<?php

Yii::import('mod.news.models.NewsTranslate');

class News extends ActiveRecord {

    const MODULE_ID = 'news';
    const route = '/news/admin/default';

    public $tags;
    public $_category = null;

    /**
     * Multilingual attrs
     */
    public $title;
    public $short_text;
    public $full_text;

    /**
     * Name of the translations model.
     */
    public $translateModelName = 'NewsTranslate';

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        Yii::import('ext.tageditor.TagEditor');
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        $categories = CategoriesModel::model()->findAll();
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
                        'category_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData($categories, 'id', 'name'),
                            'empty' => Yii::t('app', 'NO'),
                            'visible'=>($categories)?true:false
                        ),
                        'short_text' => array(
                            'type' => 'textarea',
                            'class' => 'editor'
                        ),
                        'full_text' => array(
                            'type' => 'textarea',
                            'class' => 'editor'
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
                'value' => 'Html::link(Html::encode($data->title),"/news/$data->seo_alias", array("target"=>"_blank"))',
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
        return '{{news}}';
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
            array('category_id', 'numerical', 'integerOnly' => true),
            array('title', 'required'),
            array('seo_alias', 'TranslitValidator'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('title, seo_alias', 'length', 'max' => 255),
            array('id, user_id, category_id, title, seo_alias, short_text, full_text, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'category' => array(self::BELONGS_TO, 'CategoriesModel', 'category_id'),
            'categories' => array(self::HAS_MANY, 'CategoriesModel', 'category_id')
        );
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
        $a['category'] = array(
            'class' => 'mod.admin.components.CategoryBehavior',
            'router' => '/news/category/index'
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
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'model' => 'mod.news.models.News',
                'owner_title' => 'title',
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


        $a['sortable'] = array(
            'class' => 'ext.sortable.SortableBehavior',
        );
        $a['preview'] = array(
            'class' => 'ext.image-attachment.ImageAttachmentBehavior',
            // size for image preview in widget
            'previewHeight' => 200,
            'previewWidth' => 300,
            // extension for image saving, can be also tiff, png or gif
            'extension' => 'jpg',
            // folder to store images
            'directory' => Yii::getPathOfAlias('webroot.uploads.news'),
            // url for images folder
            'url' => Yii::app()->request->baseUrl . '/uploads/news',
            // image versions
            'versions' => array(
                'small' => array(
                    'resize' => array(200, null),
                ),
                'medium' => array(
                    'resize' => array(800, null),
                )
            )
        );


        return $a;
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

        $criteria->with = array('user', 'translate', 'category');

        if (isset($_GET['tag']))
            $criteria->addSearchCondition('tags', $_GET['tag']);
        if (isset($_GET['category'])) {
            $cat = $_GET['category'];
            $criteria->params = array(':cid' => $cat);
            if (is_numeric($cat)) {
                $criteria->addCondition('t.category_id = :cid', 'OR');
            } else {
                $criteria->addCondition('category.seo_alias = :cid', 'OR');
            }
        }
        $criteria->compare('t.id', $this->id);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('translate.title', $this->title, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('translate.full_text', $this->full_text, true);
        $criteria->compare('translate.short_text', $this->short_text, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('t.switch', $this->switch);
        $criteria->compare('category.name', $this->category_id);



        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => self::getCSort()
        ));
    }

}
