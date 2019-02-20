<div class="table-responsive">
<table class="table table-striped table-bordered table-condensed" id="container-param-<?= $model->id ?>" style="margin-top:30px">
    <tr>
        <th>Шаблон</th>
        <th>Объект</th>
        <th class="text-center" width="10%"><?= Yii::t('app', 'OPTIONS') ?></th>
    </tr>
    <?php
    $params = SeoParams::model()->findAllByAttributes(array('url_id' => $model->id));
    foreach ($params as $param) {
        list($object,$parameter) = explode('/',$param->obj);
        //$paramrep = str_replace('{', '', $parameter);
        //$paramrep = str_replace('}', '', $paramrep);
        //$paramrep = str_replace('.', '', $paramrep);
        $paramrep = str_replace('/','-',$param->obj)
        ?>
        <tr id="<?= $param->id ?>" data-id="<?= $paramrep .'-'. $param->id ?>">
            <td>
                <?php echo Html::hiddenField("param[$model->id][$param->obj]", $parameter); ?>
                <?php //echo Html::hiddenField("param[$model->id][$model->name][]",$param->obj);?>
                <code>{<?php echo $parameter ?>}</code>
            </td>
            <td>

                <?php

                echo $object;

                ?>
            </td>
            <td class="text-center">
                <a data-id="<?= $param->id ?>" href="javascript:void(0);" class="deleteproperty btn btn-xs btn-danger"><i class="icon-delete"></i></a>
            </td>
        </tr>
    <?php } ?>
</table>
</div>