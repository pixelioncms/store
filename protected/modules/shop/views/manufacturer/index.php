<ul>
    <?php foreach($this->dataModel as $data){ ?>
    <li><?= Html::link($data->name,$data->getUrl())?></li>
    <?php } ?>
</ul>