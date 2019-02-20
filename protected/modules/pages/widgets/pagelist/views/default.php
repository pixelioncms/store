<?php foreach($model as $row){ ?>
                <li class="dropdown">
                    <?= Html::link($row->title,$row->getLinkUrl()); ?>

                </li>

<?php } ?>

