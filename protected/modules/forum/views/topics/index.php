<h1><?= $this->pageName; ?></h1>
<?php if(isset($categories)) { ?>
<table class="table table-striped">
    <tr>
        <td>Name</td>
        <td>Topics</td>
        <td>Posts</td>
        <td>Last post</td>
    </tr>

    <?php foreach ($categories as $data) { ?>
        <tr>
            <td>
        <?= Html::link($data->name, $data->getUrl()) ?>
                <div class="text-muted"><?= $data->hint ?></div>
                 <?= Html::link('<i class="icon-add"></i>', Yii::app()->createUrl('/forum/default/addCat', array('parent_id' => $data->id))) ?>
          
            
                <?php foreach ($data->children()->findAll() as $subdata) { ?>
                        <?= Html::link($subdata->name, $subdata->getUrl()) ?>
                <?php } ?>
            
            </td>
            <td>1</td>
            <td>23</td>
            <td>

            
            </td>
        </tr>
    <?php } ?>

</table>
<?php } ?>