<h1><?=$this->pageName;?></h1>


<div class="row sitemap-page">
    <div class="col-md-4">
        <h2>Страницы</h2>
        <?php
        echo Html::tag('ul',array('style'=>'list-style:none'));
        foreach($pages['data'] as $data){
            echo Html::tag('li');
            echo Html::link($data['title'],array($pages['route'], 'url'=>$data['url']));
            echo Html::closeTag('li');
        }

        echo Html::tag('li');
        echo Html::link('Контакты',array('/contacts'));
        echo Html::closeTag('li');
        echo Html::closeTag('ul');
        ?>
    </div>
    <div class=" col-md-4">
        <h2>Каталог</h2>
        <?= $categories;?>
    </div>
    <div class=" col-md-4">
        <h2>Производители</h2>
        <?php
        echo Html::tag('ul',array('style'=>'list-style:none'));
        foreach($manufacturers['data'] as $data){
            echo Html::tag('li');
            echo Html::link($data['title'],array($manufacturers['route'], 'seo_alias'=>$data['seo_alias']));
            echo Html::closeTag('li');
        }

        echo Html::closeTag('ul');
        ?>
    </div>

</div>










