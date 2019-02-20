<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript">init_translitter('CategoriesModel','<?= $model->primaryKey; ?>');</script>