<?php

Yii::import('mod.pages.models.PageTranslate');

class Page extends ActiveRecord
{

    const route = '/pages/admin/default';
    const MODULE_ID = 'pages';

    /**
     * Multilingual attrs
     */
    public $title;
    public $full_text;
    public $in_menu;

    public $enableAttachment = true;
    /**
     * Name of the translations model.
     */
    public $translateModelName = 'PageTranslate';

    public function getForm()
    {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
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
                            'visible' => (Yii::app()->settings->get('app', 'translate_object_url')) ? false : true
                        ),
                        'full_text' => array(
                            'type' => 'TinymceArea',
                        ),
                        Yii::app()->controller->widget('ext.attachment.AttachmentWidget', array(
                            'model' => $this,

                        ), true),
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
                        'in_menu' => array(
                            'type' => 'checkbox',
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

    public function getTitle()
    {
        if ($this->isEditMode()) {
            $html = '<form action="' . $this->getUpdateUrl() . '" method="POST">';
            $html .= '<span id="Page[title]" class="edit_mode_title">' . $this->title . '</span>';
            $html .= '</form>';
            return $html;
        } else {
            return $this->title;
        }
    }

    public function getFullText()
    {
        if ($this->isEditMode()) {
            $html = '<form action="' . $this->getUpdateUrl() . '" method="POST">';
            $html .= '<div id="Page[full_text]" class="edit_mode_text">' . $this->full_text . '</div>';
            $html .= '</form>';
            return $html;
        } else {
            return Html::text($this->full_text);
        }
    }

    public function getGridColumns()
    {
        return array(
            array(
                'name' => 'title',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'text-left'),
                'value' => 'Html::link(Html::encode($data->title),"/page/$data->seo_alias", array("target"=>"_blank"))',
            ),
            array(
                'name' => 'user_id',
                'type' => 'raw',
                'value' => 'CMS::userLink($data->user)',
                'htmlOptions' => array('class' => 'text-center'),
            ),
            array(
                'name' => 'views',
                'type' => 'html',
                'value' => 'Html::tag("span", array("class"=>"badge badge-secondary"), $data->views, true)',
                'htmlOptions' => array('class' => 'text-center'),
            ),
            array(
                'name' => 'date_create',
                'value' => 'CMS::date($data->date_create)',
                'htmlOptions' => array('class' => 'text-center'),
            ),
            array(
                'name' => 'date_update',
                'value' => 'CMS::date($data->date_update)',
                'htmlOptions' => array('class' => 'text-center'),
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn'),
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return static the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{page}}';
    }

    public function defaultScope()
    {
        return array(
            'order' => 'date_create DESC',
        );
    }

    public function scopes()
    {
        return CMap::mergeArray(array(
            'inMenu' => array(
                'condition' => 'in_menu = 1',
            ),
        ), parent::scopes());
    }

    /**
     * Find page by url.
     * Scope.
     * @param string Page url
     * @return Page
     */
    public function withUrl($url)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $url)
        ));

        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('full_text', 'type', 'type' => 'string'),
            array('title, full_text', 'required'),
            array('seo_alias', 'TranslitValidator', 'translitAttribute' => 'title'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('title, seo_alias', 'length', 'max' => 255),
            array('title, full_text', 'length', 'min' => 3),
            array('in_menu', 'numerical', 'integerOnly' => true),
            array('id, in_menu, user_id, title, seo_alias, full_text, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return CMap::mergeArray(array(
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'category' => array(self::BELONGS_TO, 'CategoriesModel', 'catid')
        ),parent::relations());
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $a = array();
        $a['seo'] = array(
            'class' => 'mod.seo.components.SeoBehavior',
            'url' => $this->getUrl()
        );
        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'translateAttributes' => array(
                'title',
                'full_text',
            ),
        );
        return CMap::mergeArray($a,parent::behaviors());
    }

    public function getUrl()
    {
        return Yii::app()->createUrl('/pages/default/index', array('url' => $this->seo_alias));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions. Used in admin search.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('user', 'translate');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.switch', $this->switch);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('translate.title', $this->title, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('translate.full_text', $this->full_text, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);


        // Create sorting by translation title
        $sort = new CSort;
        $sort->attributes = array(
            '*',
            'title' => array(
                'asc' => 'translate.title',
                'desc' => 'translate.title DESC',
            )
        );

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
    }

}
