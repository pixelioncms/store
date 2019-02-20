
<table class="table table-striped table-bordered">
    <tr>
        <th>Name</th>
        <th class="text-center">Topics</th>
        <th class="text-center">Posts</th>
        <th>Last post</th>
    </tr>

    <?php foreach ($model->parents as $data) { ?>
        <tr>
            <td><?= Html::link($data->title, $data->getUrl()) ?><div class="text-muted"><?= $data->description ?></div></td>
            <td class="text-center"><?= $data->parentsCount ?></td>
            <td class="text-center"><?= $data->postsCount ?></td>
            <td>by 
                <?= Html::link($data->posts->user->login, '') ?>
                <div><?php echo CMS::date($data->posts[0]->date_create) ?></div>
            </td>
        </tr>
    <?php } ?>

</table>