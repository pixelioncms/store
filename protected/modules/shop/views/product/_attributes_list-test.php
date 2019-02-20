<?php
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

if (!empty($data)) { ?>
    <table class="table table-striped" id="attributes-list">

        <?php foreach ($data as $name => $value) { ?>
            <tr>
                <td><?= $name ?></td>
                <td><?= $value ?></td>
            </tr>
        <?php } ?>

        <?php 
        
              function sortByStandard($a, $b) {
                            return cmp($a, $b); //strnatcmp
                        }
                            //usort($groups, 'sortByStandard');

               
               
        foreach ($groups as $group_name => $attributes) { ?>
            <tr>
                <th colspan="2"><?= $group_name ?></th>
            </tr>
            <?php
            if (isset($attributes)) {
                foreach ($attributes as $obj) {
                    ?>
                    <tr>
                        <td><?= $obj['name'] ?></td>
                        <td><?= $obj['value'] ?></td>
                    </tr>
                <?php } ?>
                <?php
            }
        }
        ?>

    </table>
<?php } ?>