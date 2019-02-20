<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Фильтр',
));
?>

<table class="table table-bordered">
    <tr>
        <td><form action="" method="get" class="">
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text">с</div></div>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 's_date',
                        'value' => $this->sdate,
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd',
                            'defaultdate' => '12.02.2012'
                        ),
                        'htmlOptions' => array(
                            'class' => 'form-control'
                        ),
                    ));
                    ?>
          
                    <div class="input-group-prepend"><div class="input-group-text">до</div></div>

                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'f_date',
                        'value' => $this->fdate,
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd',
                        //   'maxDate'=> '+1D', 
                        ),
                        'htmlOptions' => array(
                            'class' => 'form-control'
                        ),
                    ));
                    ?>
                </div>



                   <?php if (!isset($_GET['domen'])) { ?>

                    &nbsp;<span style="vertical-align: middle;"></span> 
                    <?php
                    if ($sort) {
                        echo Html::dropDownList('sort',$this->sort,array('ho' => Yii::t('StatsModule.default', 'HOSTS'), 'hi' => Yii::t('StatsModule.default', 'HITS')),array('class'=>'form-control'));
                    }
                }
                ?>
                <?php if (Yii::app()->request->getParam('engin')) echo "<input name='engin' value=" . Yii::app()->request->getParam('engin') . " type='hidden'>"; ?>
                <?php if (Yii::app()->request->getParam('domen')) echo "<input name='domen' value=" . Yii::app()->request->getParam('domen') . " type='hidden'>"; ?>
                <?php if (Yii::app()->request->getParam('brw')) echo "<input name='brw' value='" . Yii::app()->request->getParam('brw') . "' type='hidden'>"; ?>
                <?php if (Yii::app()->request->getParam('qq')) echo "<input name='qq' value='" . Yii::app()->request->getParam('qq') . "' type='hidden'>"; ?>
                <?php if (Yii::app()->request->getParam('domen') || !empty(Yii::app()->request->getParam('engin'))) { ?>
                    &nbsp;<span>строка</span> <input type=text name="str_f"  value="<?php if (Yii::app()->request->getParam('str_f')) echo Yii::app()->request->getParam('str_f'); ?>">
                <?php } ?>
                <input class="btn btn-success" type=submit value="Показать!">
            </form></td>
    </tr>
</table>
<?php Yii::app()->tpl->closeWidget(); ?>
