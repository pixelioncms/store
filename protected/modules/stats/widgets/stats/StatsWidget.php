<?php
Yii::import('mod.stats.components.StatsHelper');
class StatsWidget extends CWidget {

    public function run() {
        $stats = Yii::app()->stats->today;

        $this->render($this->skin, array(
            'hits' => $stats['hits'],
            'hosts' => $stats['hosts'],
            'search' => $stats['search'],
            'sites' => $stats['sites']
        ));
    }

}
?>
