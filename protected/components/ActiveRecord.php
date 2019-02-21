<?php

/**
 * ActiveRecord class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CActiveRecord
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
Yii::import('app.traits.ModelTranslate');

class ActiveRecord extends CActiveRecord
{

    use ModelTranslate;

    const MODULE_ID = null;

    /**
     * If use timeline
     * @timeline boolean
     */
    public $timeline = false;
    protected $_attrLabels = array();
    public $maxOrdern;
    public $disallow_delete = array();
    public $disallow_update = array();
	public $disallow_switch = array();

    public $enableAttachment = false;
    public $attachmentConfig = array(); //no use

    const route_update = 'update';
    const route_delete = 'delete';
    const route_switch = 'switch';
    const route_create = 'create';
    const route = null;



    /**
     * Special for widget ext.admin.frontControl
     *
     * @return string
     * @param $params
     * @throws CException
     */
    public function getCreateUrl($params=array())
    {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_create,$params);
        } else {
            throw new CException(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_create',
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     * @throws CException
     */
    public function getDeleteUrl()
    {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_delete, array(
                'model' => get_class($this),
                'id' => $this->id
            ));
        } else {
            throw new CException(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_delete',
                '{model}' => get_class($this)
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     * @throws CException
     */
    public function getUpdateUrl($params=array())
    {
        if (static::route) {
            if($params){
                $params = CMap::mergeArray(array(
                    'id' => $this->id
                ),$params);
            }else{
                $params = array(
                    'id' => $this->id
                );
            }
            return Yii::app()->createUrl(static::route . '/' . static::route_update, $params);
        } else {
            throw new CException(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_update',
                '{model}' => get_class($this)
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     * @throws CException
     */
    public function getSwitchUrl()
    {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_switch, array(
                'model' => get_class($this),
                'switch' => 0,
                'id' => $this->id
            ));
        } else {
            throw new CException(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_switch',
                '{model}' => get_class($this)
            )));
        }
    }

    public function uploadFile($attr, $dir, $old_image = null)
    {
        $file = CUploadedFile::getInstance($this, $attr);
        $path = Yii::getPathOfAlias($dir) . DS;
        //TODO добавить проверку на наличие папки.
        if (isset($file)) {
            if ($old_image && file_exists($path . $old_image))
                unlink($path . $old_image);
            $newname = CMS::gen(10) . "." . $file->extensionName;
            if (in_array($file->extensionName, array('jpg', 'jpeg', 'png', 'gif'))) { //Загрузка для изображений
                $img = Yii::app()->img;
                $img->load($file->tempName);
                $img->save($path . $newname);
            } else {

                $file->saveAs($path . $newname);
            }

            $this->$attr = (string)$newname;
        } else {

            $this->$attr = (string)$old_image;
        }
        return $this->$attr;
    }

    public function getColumnSearch($array = array())
    {
        $col = $this->gridColumns;
        $result = array();
        if (isset($col['DEFAULT_COLUMNS'])) {
            foreach ($col['DEFAULT_COLUMNS'] as $t) {
                $result[] = $t;
            }
        }
        foreach ($array as $column_key => $s) {
            $result[] = $col[$column_key];
        }

        if (isset($col['DEFAULT_CONTROL']))
            $result[] = $col['DEFAULT_CONTROL'];

        return $result;
    }

    public function init()
    {
        Yii::import('app.managers.CManagerModelEvent');
        CManagerModelEvent::attachEvents($this);
    }

    public function isString($attribute)
    {
        if (Yii::app()->user->isEditMode) {
            $html = '<form action="' . $this->getUpdateUrl() . '" method="POST">';
            $html .= '<span id="' . get_class($this) . '[' . $attribute . ']" class="edit_mode_title">' . $this->$attribute . '</span>';
            $html .= '</form>';
            return $html;
        } else {
            return Html::text($this->$attribute);
        }
    }

    public function isArea($attribute)
    {
        if (Yii::app()->user->isEditMode) {
            $html = '<form action="' . $this->getUpdateUrl() . '" method="POST">';
            $html .= '<div id="' . get_class($this) . '[' . $attribute . ']" class="edit_mode_text">' . $this->$attribute . '</div>';
            $html .= '</form>';
            return $html;
        } else {
            return Html::text($this->$attribute);
        }
    }

    /**
     * @param bool $mSuccess Message of success
     * @param bool $mError Message of error
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     */
    public function save($mSuccess = true, $mError = true, $runValidation = true, $attributes = null)
    {
        $message = Yii::t('app', ($this->isNewRecord) ? 'SUCCESS_CREATE' : 'SUCCESS_UPDATE');

        if (parent::save($runValidation, $attributes)) {
            if ($mSuccess) {


                if (Yii::app()->request->getPost('json2')) {
                    //    if(Yii::app()->user->getIsEditMode2()){


                    header('Content-Type: application/json; charset="'.Yii::app()->charset.'"');
                    $json=array();
                    $json['success']=true;
                    $json['message']=$message;
                    $json['valid']=true;
                    $json['data']=$this->getAttributes();
                    echo CJSON::encode($json);
                    Yii::app()->end();
                    //}
                } else {
                    if (method_exists(Yii::app()->controller, 'setNotify')) {
                        Yii::app()->controller->setNotify($message, 'success');
                    }
                }
               // Yii::app()->user->setFlash('success',$message);
            }
            return true;
        } else {
            if ($mError && method_exists(Yii::app()->controller, 'setNotify')) {
                Yii::app()->controller->setNotify(Yii::t('app', ($this->isNewRecord) ? 'ERROR_CREATE' : 'ERROR_UPDATE'), 'danger');
            }
            return false;
        }
    }

    public function validate($attributes = null, $clearErrors = true)
    {
        if (parent::validate($attributes, $clearErrors)) {

            return true;
        } else {
            $message = Yii::t('app', 'ERROR_VALIDATE');
            if (Yii::app()->controller instanceof Controller) {
                if (Yii::app()->request->getPost('json')) {
                    echo CJSON::encode(array(
                        'status' => 'error',
                        'message' => $message,
                        'errors' => $this->getErrors()
                    ));
                    Yii::app()->end();
                }
            } else {
                if (method_exists(Yii::app()->controller, 'setNotify')) {
                    Yii::app()->controller->setNotify($message, 'danger');
                }
            }
            return false;
        }
    }

    public function attributeLabels()
    {
        $lang = Yii::app()->languageManager->active->code;
        $model = get_class($this);
        $module_id = static::MODULE_ID;
        $filePath = Yii::getPathOfAlias("mod.{$module_id}.messages.{$lang}") . DS . $model . '.php';
        foreach ($this->behaviors() as $key => $b) {
            if (isset($b['translateAttributes'])) {
                foreach ($b['translateAttributes'] as $attr) {
                    $this->_attrLabels[$attr] = self::t(strtoupper($attr));
                }
            }
        }
        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = self::t(strtoupper($attr));
        }
        if (!file_exists($filePath)) {
            Yii::app()->user->setFlash('warning', 'Модель "' . $model . '", не может найти файл переводов: <b>' . $filePath . '</b> ');
        }
        return $this->_attrLabels;
    }

    //Todo: no test
    public function beforeDelete2()
    {
        if (in_array($this->id, $this->disallow_delete)) {
            return false;
        } else {
            return parent::beforeDelete();
        }
    }
	
    public function beforeSave()
    {
        if (parent::beforeSave()) {
            //create
            if ($this->isNewRecord) {
                if (isset($this->tableSchema->columns['ip_create'])) {
                    //Текущий IP адресс, автора добавление
                    $this->ip_create = Yii::app()->request->userHostAddress;
                }
                if(!Yii::app() instanceof CConsoleApplication) {
                    if (isset($this->tableSchema->columns['user_id'])) {
                        $this->user_id = (Yii::app()->user->isGuest) ? NULL : Yii::app()->user->id;
                    }
                }
                if (isset($this->tableSchema->columns['user_agent'])) {
                    $this->user_agent = Yii::app()->request->userAgent;
                }
                if (isset($this->tableSchema->columns['date_create'])) {
                    $this->date_create = date('Y-m-d H:i:s');
                }
                if (isset($this->tableSchema->columns['ordern'])) {
                    if (!isset($this->ordern)) {
                        $row = $this->model()->find(array('select' => 'max(ordern) AS maxOrdern'));
                        $this->ordern = $row['maxOrdern'] + 1;
                    }
                }
                //update
            } else {
                if (isset($this->tableSchema->columns['date_update'])) {
                    $this->date_update = date('Y-m-d H:i:s');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function getObjectNext()
    {
        $model = $this;
        $cr = new CDbCriteria();
        $cr->condition = '`t`.`id` > ' . $this->id;
        $cr->order = '`t`.`id` ASC, `t`.`ordern` ASC';
        $record = $model::model()->find($cr);
        return $record;
    }

    public function getObjectPrev()
    {
        $model = $this;
        $cr = new CDbCriteria();
        $cr->condition = '`t`.`id` < ' . $this->id;
        $cr->order = '`t`.`id` DESC, `t`.`ordern` DESC';
        $record = $model::model()->find($cr);
        return $record;
    }

    /**
     *
     * @param string $nextOrPrev next|prev
     * @param int $cid Category id
     * @param array $modelParams
     * @return type
     */
    public function getNextOrPrev($nextOrPrev, $cid = false, $modelParams = array())
    {
        $model = $this;
        $records = NULL;

        if ($nextOrPrev == "prev")
            $order = "id ASC";
        if ($nextOrPrev == "next")
            $order = "id DESC";

        if (!isset($modelParams['select']))
            $modelParams['select'] = '*';

        $modelParams['condition'] = '`t`.`switch`=1';
        // if ($cid) {
        //  $modelParams['params'] = array(':cid' => $cid);
        // }
        $modelParams['order'] = $order;

        $records = $model::model()->findAll($modelParams);

        foreach ($records as $i => $r) {
            if ($r->id == $this->id) {
                return $records[$i + 1] ? $records[$i + 1] : null;
            } else {
                return null;
            }
        }
        return null;
    }

    /* THIS RULES FOR BEHAVIORS NO USE THIS IS EXAMPLE! */

    public function rules2()
    {
        $rules = array(/* your rules */);

        //add all rules from attached behaviors
        $behaviors = $this->behaviors();
        foreach ($behaviors as $key => $behavior) {
            if (method_exists($this->{$key}, 'rules'))
                $rules += $this->{$key}->rules();
        }
        return $rules;
    }

    public function exclude($pks = array())
    {
        $criteria = new CDbCriteria;
        $criteria->addInCondition('t.id!', $pks);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function between($start, $end, $attribute = 't.date_create')
    {
        $criteria = new CDbCriteria;
        $criteria->addBetweenCondition($attribute, $start, $end);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    public function limited($limit = null)
    {
        $this->getDbCriteria()->mergeWith(array(
            'limit' => $limit,
        ));
        return $this;
    }

    /**
     * Default model scopes.
     *
     * published
     * @return array
     */
    public function scopes()
    {
        $alias = $this->getTableAlias(true);
        $scopes = array();
        if (isset($this->tableSchema->columns['switch'])) {

            $scopes['published'] = array('condition' => $alias . '.`switch` = 1');
        }
        $scopes['random'] = array('order' => 'RAND()');
        return $scopes;
    }

    /**
     * Разделение текста на страницы
     * @param string $attr
     * @return string
     */
    public function pageBreak($attr = false)
    {
        if ($attr) {
            $pagerClass = new LinkPager;

            $pag = intval(Yii::app()->request->getParam('pb'));
            $conpag = explode("<!-- pagebreak -->", $this->$attr);
            $pageno = count($conpag);
            $pag = ($pag == "" || $pag < 1) ? 1 : $pag;
            if ($pag > $pageno)
                $pag = $pageno;
            $arrayelement = (int)$pag;
            $arrayelement--;
            $content = $conpag[$arrayelement];
            $content .= $pagerClass->num_pages($this->getUrl(), $pageno);
            return $content;
        }
    }

    public $behaviors = array();

    public function behaviors()
    {
        $mid = static::MODULE_ID;
        if (isset($this->tableSchema->columns['ordern'])) {
            $this->behaviors['sortable'] = array(
                'class' => 'ext.sortable.SortableBehavior',
            );
        }

        if ($this->enableAttachment) {
            $this->behaviors['attachment'] = array(
                'class' => 'ext.attachment.components.AttachmentBehavior',
                'attachmentAttributes' => array(
                    'model' => isset($this->enableAttachment['model']) ? $this->enableAttachment['model'] : "mod.{$mid}.models." . get_class($this),
                    'watermark' => isset($this->enableAttachment['watermark']) ? $this->enableAttachment['watermark'] : false,
                    'watermark_corner' => isset($this->enableAttachment['watermark_corner']) ? $this->enableAttachment['watermark_corner'] : 4,
                    'watermark_offsetY' => isset($this->enableAttachment['watermark_offsetY']) ? $this->enableAttachment['watermark_offsetY'] : 10,
                    'watermark_offsetX' => isset($this->enableAttachment['watermark_offsetX']) ? $this->enableAttachment['watermark_offsetX'] : 10,
                    'genParam' => isset($this->enableAttachment['genParam']) ? $this->enableAttachment['genParam'] : 'name',
                    'genType' => isset($this->enableAttachment['genType']) ? $this->enableAttachment['genType'] : 'random',
                    'path' => isset($this->enableAttachment['path']) ? $this->enableAttachment['path'] : $mid,
                    'max' => isset($this->enableAttachment['max']) ? $this->enableAttachment['max'] : -1,
                    'multiple' => isset($this->enableAttachment['multiple']) ? $this->enableAttachment['multiple'] : true,
                )
            );
        }

        return $this->behaviors;
    }

    public function relations()
    {
        $mid = static::MODULE_ID;
        $relations = array();
        if ($this->enableAttachment) {
            $relations['attachments'] = array(self::HAS_MANY, 'AttachmentModel', 'object_id', 'condition' => '`attachments`.`model`="' . "mod.{$mid}.models." . get_class($this) . '"','order'=>'`attachments`.`ordern` DESC');
            $relations['attachmentsMain'] = array(self::HAS_ONE, 'AttachmentModel', 'object_id', 'condition' => '`attachmentsMain`.`is_main`=1 AND `attachmentsMain`.`model`="' . "mod.{$mid}.models." . get_class($this) . '"','order'=>'`attachmentsMain`.`ordern` DESC');
            $relations['attachmentsNoMain'] = array(self::HAS_MANY, 'AttachmentModel', 'object_id', 'condition' => '`attachmentsNoMain`.`is_main`=0 AND `attachmentsNoMain`.`model`="' . "mod.{$mid}.models." . get_class($this) . '"','order'=>'`attachmentsNoMain`.`ordern` DESC');
        }
        return $relations;
    }

    //TODO no function
    public function setBehaviors($array)
    {
        return false;
    }

}
