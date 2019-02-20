<div class="row">
    <div class="col-lg-7">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => Yii::t('DeliveryModule.default', 'CREATE_DELIVERY'),
        ));
        ?>
        <div id="response-box">
            <?php echo $this->renderPartial('form', array('users' => $users, 'delivery' => $delivery, 'model' => $model, 'mails' => $mails)); ?>
        </div>
        <?php
        Yii::app()->tpl->closeWidget();
        ?>
    </div>


    <div class="col-lg-5">
        <div class="card bg-light">
            <div class="card-header">
                <div class="card-title" style="padding: 15px 15px 0 15px;">
                    <div class="row">
                        <div class="col-sm-5">
                            <div id="progress-send"></div>
                        </div>
                        <div class="col-sm-7">
                            <div class="progress d-none">
                                <div class="progress-bar progress-bar-success progress-bar-striped progress-bar-animated"
                                     style="width:0;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="list-group list-group-flush" id="sended-result"></div>

        </div>
    </div>
</div>


