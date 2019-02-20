<h1>Заказ #<?php echo $_GET['orderid']?></h1>
<table border="1" width="100%">
    <tr>
    <th width="10%">Картинка</th>
    <th width="50%">Название</th>
    <th width="30%">Пар в ящике</th>
    <th width="30%">Цена</th>
    </tr>
    <?php $total=0; ?>
    <?php foreach($dataProvider->getData() as $row){ ?>
    <?php $total +=$row->prd->price * $row->quantity ?>

    <tr>
        <td><?php echo CHtml::image($row->prd->mainImage->getUrl('50x50'), '');
        // echo CHtml::image('/assets/products/50x50/'.$row->mainImage->name, ''); ?></td>
        <td><?= $row->name;?></td>
        <td align="center"><?= $row->quantity;?></td>
        <td align="center"><?= $row->prd->price;?> <?php echo Yii::app()->currency->active->symbol?></td>
    </tr>
    <?php } ?>
</table><br/><br/>
<div style="text-align:right">Общая цена: <span id="total_price"><?php echo $total?> <?php echo Yii::app()->currency->active->symbol?></span></div>
