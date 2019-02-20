<div class="online">
    <ul class="list-group list-group-flush">

        <li class="list-group-item">
            <div class="row ">

                <?php foreach ($online['totals']['roles'] as $roleName => $roleCount) { ?>
                    <div class="col-sm-6"><?php
                        if (!in_array($roleName, array('Guest', 'SearchBot'))) {
                            echo Rights::getRoles()[$roleName];
                        } else {
                            echo Yii::t('app',strtoupper($roleName));
                        }
                        ?>: <b class="float-right2"><?= $roleCount; ?></b>
                    </div>


                <?php } ?>

            </div>
        </li>
        <li class="list-group-item"><?= Yii::t('app', 'TOTAL') ?>: <b class="badge badge-secondary"
                                                                      style="float:none;"><?= $online['totals']['all']; ?></b>
        </li>
    </ul>


    <div id="accordion" class="card2-group ">
        <?php
        $this->widget('ListView', array(
            'dataProvider' => $model->search(),
            'itemView' => '_view',
            'template' => "{items}\n{pager}",
        ));
        ?>
    </div>

</div>