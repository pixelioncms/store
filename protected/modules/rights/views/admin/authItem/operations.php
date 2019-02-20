


    <div id="operations">

        <?php
        $this->beginClip('help');
        Yii::app()->tpl->alert('info', Rights::t('default', 'An operation is a permission to perform a single operation, for example accessing a certain controller action.') . '<br/>'. Rights::t('default', 'Operations exist below tasks in the authorization hierarchy and can therefore only inherit from other operations.'), false);

        Yii::app()->tpl->alert('info', Rights::t('default', 'Values within square brackets tell how many children each item has.'));

        $this->endClip();
        ?> 
        <?= $this->clips['help'];?>

        <?php
        $this->widget('ext.adminList.GridView', array(//zii.widgets.grid.CGridView
            'dataProvider' => $dataProvider,
            'template' => '{items}',
            'selectableRows' => false,
            'name' => $this->pageName,
            'autoColumns' => false,
            'emptyText' => Rights::t('default', 'No operations found.'),
            'htmlOptions' => array('class' => 'grid-view operation-table sortable-table'),
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => Rights::t('default', 'Name'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'name-column'),
                    'value' => '$data->getGridNameLink()',
                ),
                array(
                    'name' => 'description',
                    'header' => Rights::t('default', 'Description'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'description-column'),
                ),
                array(
                    'name' => 'bizRule',
                    'header' => Rights::t('default', 'Business rule'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'bizrule-column'),
                    'visible' => Rights::module()->enableBizRule === true,
                ),
                array(
                    'name' => 'data',
                    'header' => Rights::t('default', 'Data'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'data-column'),
                    'visible' => Rights::module()->enableBizRuleData === true,
                ),
                array(
                    'header' => '&nbsp;',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'actions-column text-center'),
                    'value' => '$data->getDeleteOperationLink()',
                ),
            )
        ));
        ?>


    </div>
