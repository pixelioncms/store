


<?php
if ($config->filter_enable_attr) {
    foreach ($attributes as $attrData) {
        ?>

        <div class="card panel-default filter-block">

            <a class="card-header" data-toggle="collapse" data-target="#filter<?= md5($attrData['title']); ?>">
                <span class="panel-title"><?= Html::encode($attrData['title']) ?></span>
            </a>
            <div class="panel-collapse collapse in" id="filter<?= md5($attrData['title']); ?>">
                <div class="card-body">
                    <ul class="filter-list">
                        <?php
                        foreach ($attrData['filters'] as $filter) {


                            // $cacheId = 'filter_' . $filter['queryKey'] . '_' . $filter['queryParam'] . '_' . $filter['count'];
                            // $result = Yii::app()->cache->get($cacheId);
                            // if ($result === false) {




                            if ($filter['count'] > 0) {
                                $url = Yii::app()->request->addUrlParam('/shop/manufacturer/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
                            } else {
                                $url = 'javascript:void(0)';
                            }

                            $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));
                            if ($filter['count'] > 0) {
                                echo Html::openTag('li');
                                // Filter link was selected.
                                if (in_array($filter['queryParam'], $queryData)) {
                                    // Create link to clear current filter
                                    $url = Yii::app()->request->removeUrlParam('/shop/manufacturer/view', $filter['queryKey'], $filter['queryParam']);
                                 //   if ($filter['count'] > 0)
                                        echo Html::link($filter['title'], $url, array('class' => 'active','rel'=>"nofollow"));
                                } else {
                                   // if ($filter['count'] > 0)
                                        echo Html::link($filter['title'], $url,array('rel'=>"nofollow"));
                                }
                               // if ($this->countAttr) {
                                 
                                   //     echo $this->getCount($filter);
                               
                               // }
                                echo Html::closeTag('li');
                            }

                            // Yii::app()->cache->set($cacheId, $result, 0); //Yii::app()->settings->get('app', 'cache_time')
                            //  }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <?php
    }
}
?>
