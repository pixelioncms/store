    <div class="form-group text-right">
        <?php if (Yii::app()->user->isSuperuser) { ?>
            <span class="dropdown">
                <a class="btn btn-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Опции форума
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="#"><i class="icon-rename"></i> Показать скрытые</a></li>
                    <li><a href="#">Показать скрытые темы</a></li>
                    <li><a href="#"><i class="icon-delete"></i> Удаление / массовое перемещение</a></li>
                </ul>
            </span>
        <?php } ?>
        <?php if ($model->checkAddTopic()) { ?>
            <?= Html::link('<i class="icon-add"></i> Новая тема', Yii::app()->createUrl('/forum/topics/addTopic', array('id' => $model->id)), array('class' => 'btn btn-success')) ?>
        <?php } ?>

    </div>