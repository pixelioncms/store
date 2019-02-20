<div class="alert alert-info">Курс валюты НБУ, на: <?=date('Y-m-d',strtotime($this->options['date']))?></div>
<table class="table">
    <tr>
        <th class="text-center">Код цифровой</th>
        <th class="text-center">Код буквенный</th>
        <th>Название валюты</th>
        <th class="text-center">Курс UAH</th>
    </tr>
    <?php foreach ($result as $cur) { ?>
        <tr>
            <td class="text-center"><?= $cur['r030'] ?></td>
            <td class="text-center"><?= $cur['cc'] ?></td>
            <td><?= $cur['txt'] ?></td>
            <td><?= $cur['rate'] ?></td>
        </tr>
<?php } ?>
</table>