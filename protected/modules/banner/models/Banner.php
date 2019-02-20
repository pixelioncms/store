<?php
Yii::import('mod.banner.models.BannerTranslate');
class Banner extends ActiveRecord {

    const MODULE_ID = 'banner';

    public $content;
    public $image;
    public $translateModelName = 'BannerTranslate';

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        Yii::import('ext.bootstrap.fileinput.FileInput');
        return new CMSForm(array(
            'showErrorSummary' => true,
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'elements' => array(
                'image' => array(
                    'type' => 'FileInput',
                    'visible' => true,
                    'options' => array(
                        'showUpload' => false,
                        'showPreview' => true,
                        'overwriteInitial' => true,
                        'showRemove' => false,
                        'showClose' => false,
                        'showCaption' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'elErrorContainer' => '#kv-avatar-errors',
                        'msgErrorClass' => 'alert alert-danger',
                        'initialPreview' => $this->getInitialPreview(),
                        'allowedFileExtensions' => array("jpg", "png", "gif"),
                        'fileActionSettings' => array(
                            'showDrag' => false
                        ),
                        'initialPreviewConfig' => array(
                            array(
                                'width' => '120px',
                                'url' => Yii::app()->createUrl('/admin/banner/default/removefile'), // server delete action
                                'key' => $this->id,
                            )
                        ),
                    ),
                    'hint' => self::t('IMAGE_HINT'),
                    'afterContent' => '<div id="kv-avatar-errors" style="display:none"></div>'
                ),
                'content' => array(
                    'type' => 'textarea',
                    'class' => 'editor'
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public function getGridColumns() {
        return array(
            array(
                'name' => 'image',
                'type' => 'html',
                'htmlOptions' => array('class' => 'image'),
                'filter' => false,
                'value' => 'Html::link(Html::image($data->getImageUrl("100x100"),"",array("class"=>"img-thumbnail")),$data->getOriginalImageUrl())'
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
                array('class' => 'ext.sortable.SortableColumn')
            //   array('class' => 'HandleColumn')
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
        return '{{banner}}';
    }

    public function defaultScope() {
        return array(
            'order' => 'ordern DESC',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('image, content', 'type', 'type' => 'string'),
            array('image', 'FileValidator', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            //array('image', 'required', 'on'=>'insert'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('id, image, content, date_update, date_create, ordern', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors() {
        $a = array();
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('date_create', 'date_update'),
        );
        $a['upload'] = array(
            'class' => 'app.behaviors.UploadfileBehavior',
            'attributes'=>array('image'),
            'dir'=>'banner'
        );
        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'translateAttributes' => array(
                'content',
            ),
        );
        return CMap::mergeArray($a, parent::behaviors());
    }

    public function relations() {
        return array(
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions. Used in admin search.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->with = array('translate');
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.image', $this->image, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('translate.content', $this->content, true);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
                // 'sort' => $sort,
        ));
    }

}
