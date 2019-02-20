
<div class="row">
    <div class="col-md-5">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => $this->pageName . ' ' . (($formModel->scenario === 'update') ? Rights::getAuthItemTypeName($formModel->type) : ''),
        ));
        ?>

        <div id="updatedAuthItem">
            <?php $this->renderPartial('_form', array('model' => $formModel)); ?>
        </div>
        <?php Yii::app()->tpl->closeWidget(); ?>
    </div>
    <div class="col-md-7">

        <?php if ($model->name !== Rights::module()->superuserName) { ?>
            <?php
            $this->widget('ext.adminList.GridView', array(
                'dataProvider' => $parentDataProvider,
                'template' => '{items}',
                'name' => Rights::t('default', 'Parents'),
                'hideHeader' => true,
                'enableHeader' => true,
                'autoColumns' => false,
                'headerOptions' => false,
                'emptyText' => Rights::t('default', 'This item has no parents.'),
                'htmlOptions' => array('class' => 'grid-view'),
                'columns' => array(
                    array(
                        'name' => 'name',
                        'header' => Rights::t('default', 'Name'),
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'name-column'),
                        'value' => '$data->getNameLink()',
                    ),
                    array(
                        'name' => 'type',
                        'header' => Rights::t('default', 'Type'),
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'type-column text-center'),
                        'value' => '$data->getTypeText()',
                    ),
                    array(
                        'header' => '&nbsp;',
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'actions-column text-center'),
                        'value' => '',
                    ),
                )
            ));
            ?>


            <?php
            $this->widget('ext.adminList.GridView', array(//zii.widgets.grid.CGridView
                'dataProvider' => $childDataProvider,
                'template' => '{items}',
                'name' => Rights::t('default', 'Children'),
                'headerOptions' => false,
                'hideHeader' => true,
                'enableHeader' => true,
                'autoColumns' => false,
                'emptyText' => Rights::t('default', 'This item has no children.'),
                //'htmlOptions' => array('class' => ''),
                'columns' => array(
                    /*  array(
                      'class' => 'CheckBoxColumn',
                      'value' => '$data->getName()',
                      'checkBoxHtmlOptions'=>array('name'=>'actions[]'),
                      ), */
                    array(
                        'name' => 'name',
                        'header' => Rights::t('default', 'Name'),
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'name-column'),
                        'value' => '$data->getNameLink()',
                    ),
                    array(
                        'name' => 'type',
                        'header' => Rights::t('default', 'Type'),
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'type-column text-center'),
                        'value' => '$data->getTypeText()',
                    ),
                    array(
                        'header' => '&nbsp;',
                        'type' => 'raw',
                        'htmlOptions' => array('class' => 'actions-column text-center'),
                        'value' => '$data->getRemoveChildLink()',
                    ),
                )
            ));
            ?>






            <?php
            Yii::app()->tpl->openWidget(array('title' => Rights::t('default', 'Add Child')));
            if ($childFormModel !== null) {
                $this->renderPartial('_childForm', array(
                    'model' => $childFormModel,
                    'itemnameSelectOptions' => $childSelectOptions,
                ));
            } else {
                Yii::app()->tpl->alert('info', Rights::t('default', 'No children available to be added to this item.'));
            }
            Yii::app()->tpl->closeWidget();
            ?>




        <?php } else { ?>
            <?php Yii::app()->tpl->alert('info', Rights::t('default', 'No relations need to be set for the superuser role.') . '<br/>' . Rights::t('default', 'Super users are always granted access implicitly.'), false); ?>

        <?php } ?>

    </div>

</div>