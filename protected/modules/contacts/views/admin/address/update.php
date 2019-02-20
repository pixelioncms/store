<div class="row">
    <div class="col-sm-12">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => $this->pageName,
        ));
        echo $model->getForm();
        Yii::app()->tpl->closeWidget();
        ?>
    </div>

</div>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Карта',
));
$this->widget('mod.contacts.widgets.map.AdminMapWidget', array(
    'options' => array('coordInput' => '#ContactsAddress_coords')
));
Yii::app()->tpl->closeWidget();
