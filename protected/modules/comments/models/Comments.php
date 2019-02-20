<?php

class Comments extends ActiveRecord
{

    const MODULE_ID = 'comments';

    const STATUS_WAITING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_SPAM = 2;

    public $defaultStatus;

    public function getForm()
    {
        return new CMSForm(array('id' => __CLASS__,
            'showErrorSummary' => false,
            'elements' => array(
                'user_agent' => array('type' => 'none'),
                'ip_create' => array('type' => 'none'),
                'text' => array('type' => 'textarea'),
                'switch' => array(
                    'type' => 'dropdownlist',
                    'items' => self::getStatuses()
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        ), $this);
    }

    public static function getStatuses()
    {
        return array(
            self::STATUS_WAITING => Yii::t('CommentsModule.admin', 'Ждет одобрения'),
            self::STATUS_APPROVED => Yii::t('CommentsModule.admin', 'Подтвержден'),
            self::STATUS_SPAM => Yii::t('CommentsModule.admin', 'Спам'),
        );
    }

    public function getStatusTitle()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->switch];
    }

    /**
     * Определяет таймаут управление комментарием
     * @return bool
     */
    public function controlTimeout()
    {
        $stime = strtotime($this->date_create) + Yii::app()->settings->get('comments', 'control_timeout');
        return (time() < $stime) ? true : false;
    }

    public function getEditLink()
    {
        $stime = strtotime($this->date_create) + Yii::app()->settings->get('comments', 'control_timeout');
        $userId = Yii::app()->user->id;
        if ($userId == $this->user_id || Yii::app()->user->isSuperuser) {
            return Html::link('<i class="icon-edit"></i>', 'javascript:void(0)', array(
                "onClick" => "$('#comment_" . $this->id . "').comment('update',{time:" . $stime . ", pk:" . $this->id . ", csrf:'" . Yii::app()->request->csrfToken . "'}); return false;",
                'class' => 'btn btn-primary',
                'title' => Yii::t('app', 'UPDATE', 1)
            ));
        }
    }

    public function getDeleteLink()
    {
        $userId = Yii::app()->user->id;
        $stime = strtotime($this->date_create) + Yii::app()->settings->get('comments', 'control_timeout');
        if ($userId == $this->user_id || Yii::app()->user->isSuperuser) {
            return Html::link('<i class="icon-delete"></i>', 'javascript:void(0)', array(
                "onClick" => "$('#comment_" . $this->id . "').comment('remove',{time:" . $stime . ", pk:" . $this->id . ", csrf:'" . Yii::app()->request->csrfToken . "'}); return false;",
                'class' => 'btn btn-danger',
                'title' => Yii::t('app', 'DELETE')
            ));
        }
    }

    public function getUser_name()
    {
        $user = $this->user;
        if (isset($user->login)) {
            return $user->login;
        } else {
            return 'Гость';
        }
    }

    public function getAvatarPath22()
    {
        $user = $this->user;
        if (isset($user->avatarPath)) {
            return $user->avatarPath;
        } else {
            return '/images/avatars/guest.png';
        }
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            //'like' => array(self::HAS_ONE, 'Like', 'id'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return ShopCategory the static model class
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
        return '{{comments}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            // array('seo_alias', 'translitFilter'),
            //   array('text', 'length', 'max' => 255),
            array('user_agent, ip_create', 'type', 'type' => 'string'),
            array('switch', 'numerical', 'integerOnly' => true),
            array('text', 'required'),
            array('text','StripTagsValidator'),
            array('text', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors()
    {
        return array(
            'like' => array(
                'class' => 'ext.like.LikeBehavior',
                'model' => 'mod.comments.models.Comments',
                'modelClass' => 'Comments',
                'nodeSave' => true
            ),
            'NestedSetBehavior' => array(
                'class' => 'app.behaviors.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true
            ),
        );
    }

    public function scopes() {
        $alias = $this->getTableAlias(true);
        return CMap::mergeArray(array(
            'new' => array('condition' => $alias . '.switch=0'),
            'active' => array(
                'condition' => $alias . '.switch=1',
            ),
        ), parent::scopes());
    }



    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('ip_create', $this->ip_create, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}