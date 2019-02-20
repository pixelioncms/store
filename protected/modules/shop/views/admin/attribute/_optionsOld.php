<?php
Yii::app()->getClientScript()
        ->registerScriptFile($this->module->assetsUrl . '/admin/attribute.options.js', CClientScript::POS_END);
?>

<style type="text/css">
    table.optionsEditTable td {
        padding: 3px;
    }
    table.optionsEditTable input[type="text"] {
        width: 200px;
    }
    table.optionsEditTable tr.copyMe {
        display: none;
    }

</style>

<table class="optionsEditTable table table-striped table-bordered">
    <thead>
        <tr>
            <?php foreach (Yii::app()->languageManager->languages as $l) { ?>
                <td>
                    <?php echo CHtml::encode($l->name) ?>
                </td>
            <?php } ?>
            <td class="text-center">
                <a class="plusOne btn btn-success" style="color:#fff" href="javascript:void(0)">
                    <i class="icon-add"></i>
                </a>

            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="copyMe">

            <?php foreach (Yii::app()->languageManager->languages as $l) { ?>
                <td>
                    <input name="sample" type="text" class="value form-control">
                </td>
            <?php } ?>
            <td class="text-center"><a href="javascript:void(0);" class="deleteRow btn btn-default"><i class="icon-delete"></i></a>

            </td>
        </tr>
        <?php
        if ($model->options) {
            foreach ($model->options as $o) {
                ?>
                <tr>

                    <?php
                    foreach (Yii::app()->languageManager->languages as $l) {
                        $o->option_translate = ShopAttributeOptionTranslate::model()->findByAttributes(array(
                            'object_id' => $o->id,
                            'language_id' => $l->id));
                        ?>
                        <td>
                            <input class="form-control" name="options[<?php echo $o->id ?>][]" type="text" value="<?php echo CHtml::encode($o->option_translate->value) ?>">
                        </td>
                    <?php } ?>
                    <td class="text-center">
                        <a href="javascript:void(0);" class="deleteRow btn btn-default"><i class="icon-delete"></i></a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <?php
                $rnd = rand(1, 9999);
                foreach (Yii::app()->languageManager->languages as $l) {
                    ?>
                    <td>
                        <input class="form-control" name="options[<?php echo $rnd ?>][]" type="text">
                    </td>
                <?php } ?>
                <td class="text-center">



                    <a href="#" class="deleteRow btn btn-default"><i class="icon-delete"></i></a>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
