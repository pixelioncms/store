  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
  

<?php
foreach ($this->model->products as $id => $group) {
?>

    <li><a href="#tabs-<?=$id?>"><?=$group['name']?></a></li>

  <?php
    echo "<b>" . $group['name'] . "</b></br>";
    ?>


    <table class="compareTable table table-bordered">
        <thead>
            <tr>
                <td width="200px"></td>
                <?php foreach ($group['items'] as $p) { ?>
                    <td>
                        <div class="products_list wish_list">
                            <?php $this->renderPartial('_product', array('data' => $p)) ?>
                        </div>
                    </td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($this->model->attributes[$id]['attr'])){
            foreach ($this->model->attributes[$id]['attr'] as $attribute) {
                $unq = array();

                foreach ($group['items'] as $product) {

                    $unq[] = (string) $product->{'eav_' . $attribute->name};
                }

                foreach (array_count_values($unq) as $pid=>$count) {
                    $flag = true;

                    if ($count == count($group['items'])) {
                        $flag = false;
                    }
                }
                if ($flag) {
                    ?>
                    <tr>
                        <td class="attr"><?= $attribute->title ?></td>
                        <?php foreach ($group['items'] as $product) {
                            ?>
                            <td>
                                <?php
                                $value = $product->{'eav_' . $attribute->name};
                                echo $value === null ? Yii::t('ShopModule.default', 'Не указано') : $value;
                                ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                }
            } } 
            ?>
        </tbody>
    </table>
<?php 
}
?>
<script>
    $(function () {
        var result = new Array();

        $('.compareTable tbody tr td:not(.attr)').each(function (k, obj) {
            var value = $.trim($(obj).text());
            var row = $(obj).closest('td');
            var clone = row.clone();

            console.log(row);
            // console.log(clone);


            /*  if (($(this).text() != '') && ($(this).text() != ' ')) {
             if (result.indexOf($(this).text()) == -1) {
             result.push(+($(this).text()));
             }
             }*/
        });
        // alert(result);
    });
</script>