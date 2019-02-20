
<?php
Yii::app()->tpl->openWidget(array('title' => $this->pageName));
echo $form;
Yii::app()->tpl->closeWidget();
?>
