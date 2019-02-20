<?php

Yii::app()->tpl->alert('info', Yii::t('app', 'Чтобы атрибут отображался в товарах его необходимо добавить к необходимому {productType}', array('{productType}' => Html::link('типу товара', '/admin/shop/productType'))))
?>


<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $form->tabs();

Yii::app()->tpl->closeWidget();
?>
