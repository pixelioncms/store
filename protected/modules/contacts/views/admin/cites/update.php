<div class="row">
    <div class="col-sm-6">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => $this->pageName,
        ));
        echo $model->getForm();
        Yii::app()->tpl->closeWidget();
        ?>
    </div>
    <div class="col-sm-6">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => 'Добавить адрес',
        ));



        echo $modelAddress->getForm();
        ?>

        <?php Yii::app()->tpl->closeWidget(); ?>
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => 'Добавить адрес',
        ));

        $modelAddress->unsetAttributes();

        $this->widget('ext.adminList.GridView', array(
            'dataProvider' => $modelAddress->search(array('city_id' => $model->id)),
            'autoColumns' => false,
            'enableHeader' => false,
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'name',
                    'type' => 'raw',
                    'value' => '$data->name',
                ),
                array(
                    'class' => 'ButtonColumn',
                    'template' => '{update}{delete}',
                    'resetFilter' => false,
                    'buttons' => array(
                        'update' => array(
                            'url' => 'Yii::app()->createUrl("/admin/contacts/address/update", array("id"=>$data->id))',
                        )
                    )
                ),
            ),
        ));
        ?>

        <?php Yii::app()->tpl->closeWidget(); ?>
    </div>
</div>
<?php
/*Yii::app()->tpl->openWidget(array(
    'title' => 'Карта',
));
$this->widget('mod.contacts.widgets.map.AdminMapWidget', array(
    'options' => array('coordInput' => '#ContactsAddress_coords')
));
Yii::app()->tpl->closeWidget();*/
