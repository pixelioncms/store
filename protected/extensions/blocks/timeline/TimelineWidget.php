<?php

class TimelineWidget extends BlockWidget {


    public function init() {
        $this->setId('timeline-widget');
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bbq');
        //TODO no work ;(
        /*$cs->registerScript('timeline', "
            var counterReloadTimeline = 0;
            var counter = " . (int)$this->config['refresh_interval'] . " / 1000;
            function countdown() {
                counter = counter-1;
              //  document.getElementById('sec').innerHTML=counter;
                if (counter == 0) {
                    counter = " . (int)$this->config['refresh_interval'] . " / 1000;
                }
            }
            setInterval(countdown, 1000);

            setInterval(function(){
                counterReloadTimeline +=1;
                if(counterReloadTimeline > 50){
                    location.reload();
                }else{
                    $.fn.yiiListView.update('timeline-items');
                }
            }, " . (int)$this->config['refresh_interval'] . ");");*/

        //   echo '<span id=sec>' . $this->config['limit'] . '</span> сек';

    }

    public function run() {
        $model = new Timeline('search');
        $this->render($this->skin, array(
            'data' => $model->search($this->config),
        ));
    }

}
