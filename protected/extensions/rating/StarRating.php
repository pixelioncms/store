<?php

class StarRating extends CStarRating {

    public $model;
    public $maxRating = 5;
    public $minRating = 1;

    public function run() {
        $cookName = 'rating'.md5(get_class($this->model) . $this->model->id);
        $this->name = 'rating_' . $this->model->id;
       // $this->id = 'rating_id_' . $this->model->id;
        $this->htmlOptions['class']= 'rating';
        $this->htmlOptions['style']= 'display:inline-block';
       // $this->htmlOptions['id']= 'rating_id111_' . $this->model->id;
        if (!$this->readOnly) {
            if (isset(Yii::app()->request->cookies[$cookName]->value)) {
                $this->readOnly = true;
            }
            $this->allowEmpty = false;
            $this->readOnly = isset(Yii::app()->request->cookies[$cookName]);
        }
        $this->value = ($this->model->rating + $this->model->votes) ? round($this->model->rating / $this->model->votes) : 0;
        $this->callback = 'js:function(){ajax_rating(' . $this->model->id . ')}';

        for ($x = 1; $x <= $this->maxRating; $x++) {
            $this->titles[$x] = Yii::t('app', 'RATING', $x);
        }

        list($name,$id)=$this->resolveNameID();
        if(isset($this->htmlOptions['id']))
            $id=$this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];

        $this->registerClientScript($id);

        echo CHtml::openTag('span',$this->htmlOptions)."\n";
        $this->renderStars($id,$name);
        echo "</span>";
    }

    public function registerClientScript($id) {

        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);


        $jsOptions = $this->getClientOptions();
        $jsOptions = empty($jsOptions) ? '' : CJavaScript::encode($jsOptions);
        $js = "jQuery('#{$id} > input').rating({$jsOptions});";
        $cs = Yii::app()->getClientScript();
        //$cs->registerCoreScript('rating');
        $cs->registerScriptFile($assetsUrl . '/js/jquery.rating.js',CClientScript::POS_END);
        $cs->registerScriptFile($assetsUrl . '/js/rating.js',CClientScript::POS_END);
        $cs->registerScript('StarRating#' . $id, $js, CClientScript::POS_END);

        if ($this->cssFile !== false)
            self::registerCssFile($this->cssFile);
    }

    /**
     * Renders the stars.
     * @param string $id the ID of the container
     * @param string $name the name of the input
     */
    protected function renderStars($id,$name)
    {
        $inputCount=(int)(($this->maxRating-$this->minRating)/$this->ratingStepSize+1);
        $starSplit=(int)($inputCount/$this->starCount);
        if($this->hasModel())
        {
            $attr=$this->attribute;
            CHtml::resolveName($this->model,$attr);
            $selection=$this->model->$attr;
        }
        else
            $selection=$this->value;
        $options=$starSplit>1 ? array('class'=>"{split:{$starSplit}}") : array();
        for($value=$this->minRating, $i=0;$i<$inputCount; ++$i, $value+=$this->ratingStepSize)
        {
            $options['id']=$id.'_'.$i;
            $options['value']=$value;
            if(isset($this->titles[$value]))
                $options['title']=$this->titles[$value];
            else
                unset($options['title']);
            echo CHtml::radioButton($name,!strcmp($value,$selection),$options) . "\n";
        }
    }
}

