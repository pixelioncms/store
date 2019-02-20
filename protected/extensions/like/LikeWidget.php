<?php

/**
 * Install
 * Add to the database "damp.sql" which is located in the folder "data"
 * Your model class need param: `likes` int(11) NOT NULL DEFAULT '0',
 * 
 * Add to your model class behaviors
 * <pre>
 * <?php
 * 'like' => array(
 *      'class' => 'ext.like.LikeBehavior',
 *      'model' => 'mod.comments.models.Comments', //alias path to model class
 *      'modelClass' => 'Comments', //name model class
 *      'nodeSave' => true //save type save() or nodeSave()
 * ),
 * ?>
 * </pre>
 * 
 * Add to AjaxController in actions:
 * <pre>
 * <?php
 * public function actions() {
 *      return array(
 *          'like.' => 'ext.like.LikeWidget',
 *      );
 * }
 * ?>
 * </pre>
 * 
 * Add to your render view (example):
 * <pre>
 * <?php
 * $this->widget('ext.like.LikeWidget', array('model'=>User::model()->findByPk(1)));
 * ?>
 * </pre>
 * 
 * @name $model Model class
 * @name $checkVoted access to likes
 * @url widget ajax/like.action & ajax/like.list
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @version 1.0
 * @copyright (c) 2015, Andrew S.
 */
class LikeWidget extends CWidget {

    protected $assetsPath;
    protected $assetsUrl;
    public $model;
    public $checkVoted = true;
    public $likes = null;

    public static function actions() {
        return array(
            'action' => array('class' => 'ext.like.actions.LikeAction'),
            'list' => array('class' => 'ext.like.actions.LikeActionHover'), // Action списка, кто голосовал
        );
    }

    public function init() {
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        if (!$this->likes != null) {
            $this->likes = $this->model->getLikes(true);
        }
        $this->id = $this->model->id;
        $this->registerScripts();
    }

    public function run() {
        $m = $this->model;
        $json = array(
            'object_id' => $m->primaryKey,
            'm' => $m->getModelClass(),
        );
        $view = ($m->checkUserLiked() && $this->checkVoted) ? 'voted' : 'view';

        $this->render($view, array(
            'counter' => $this->likes,
            'status' => $m->getLikeStatus(),
            'json' => CJSON::encode($json),
        ));
    }

    protected function registerScripts() {
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/js/like.js', CClientScript::POS_HEAD);
            $cs->registerCssFile($this->assetsUrl . '/css/like.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}