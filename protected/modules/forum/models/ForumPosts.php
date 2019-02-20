<?php

class ForumPosts extends ActiveRecord {

    const MODULE_ID = 'forum';
    const route = '/forum/admin/default';

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
                'kartinka' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_IMG2'),
                    'elements' => array(
                        'files' => array(
                            'type' => 'FileInput',
                            'htmlOptions' => array('multiple' => true),
                            'options' => array(
                                'showUpload' => false,
                                'showPreview' => true,
                                //  'maxFileCount'=> 2,
                                //  'validateInitialCount'=> true,
                                'uploadAsync' => false,
                                'maxFileSize' => 35000,
                                // 'showClose' => false,
                                //'showCaption' => true,
                                // 'browseLabel' => '',
                                //'removeLabel' => '',
                                'overwriteInitial' => false,
                                'elErrorContainer' => '#kv-avatar-errors',
                                'msgErrorClass' => 'alert alert-danger',
                                'initialPreview' => $this->initialPreview(),
                                //  'defaultPreviewContent' => '<img src="/uploads/'.$this->filesList[0]['filename'].'" alt="Your Avatar">',
                                //'layoutTemplates' => "{main2: '{preview}  {remove} {browse}'}",
                                'allowedFileExtensions' => array("jpg", "png", "gif"),
                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                'initialPreviewFileType' => 'image', // image is the default and can be overridden in config below
                                'initialPreviewConfig' => array(
                                    array('caption' => "People-1.jpg", 'size' => 576237, 'width' => "120px", 'url' => "/admin/news/default/deleteFile", 'key' => 1),
                                    array('caption' => "People-2.jpg", 'size' => 932882, 'width' => "120px", 'url' => "/admin/news/default/deleteFile", 'key' => 2),
                                ),
                                /*      'uploadExtraData'=>"js:function() {  // callback example
                                  var out = {}, key, i = 0;
                                  $('.kv-input:visible').each(function() {
                                  var el = $(this);
                                  key = el.hasClass('kv-new') ? 'new_' + i : 'init_' + i;
                                  out[key] = el.val();
                                  i++;
                                  });
                                  return out;
                                  }", */
                                'uploadExtraData' => array(
                                    'img_key' => "1000",
                                    'img_keywords' => "happy, places",
                                ),
                                'previewSettings' => array(
                                    'image' => array('width' => "120px", 'height' => "120px"),
                                )
                            ),
                            'afterContent' => '<div id="kv-avatar-errors" style="display:none"></div>'
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
        return '{{forum_posts}}';
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

    public function afterSave() {


        if (!Yii::app()->user->isGuest) {
            $user = User::model()->findByPk($this->user_id);
            $user->saveCounters(array('forum_posts_count' => 1));
        }


        return parent::afterSave();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('text, edit_reason', 'type', 'type' => 'string'),
            array('text', 'length', 'min' => 3),
            array('text, topic_id, user_id', 'required'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('text', 'length', 'max' => 255),
            array('edit_user_id, user_id', 'numerical', 'integerOnly' => true),
            array('id, user_id, edit_user_id, edit_reason, edit_datetime, seo_alias, text, full_text, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'userEdit' => array(self::BELONGS_TO, 'User', 'edit_user_id'),
            'topic' => array(self::BELONGS_TO, 'ForumTopics', 'topic_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();
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

        $criteria->with = array('user', 'userEdit');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('userEdit.username', $this->edit_user_id, true);
        $criteria->compare('translate.title', $this->title, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('translate.full_text', $this->full_text, true);
        $criteria->compare('translate.short_text', $this->short_text, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('t.switch', $this->switch);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => self::getCSort(),
            'pagination' => array('pageVar' => 'page'/* ,'route'=>'/news' */)
        ));
    }

    public function isEditPost() {
        if (Yii::app()->user->isSuperuser) {
            return true;
        } else {
            if ($this->user_id == Yii::app()->user->id) {
            if (time() < strtotime($this->date_create) + (int) Yii::app()->settings->get('forum', 'edit_post_time') * 60) {
                return true;
            } else {
                return false;
            }
            }else{
                return false;
            }
        }
    }

}
