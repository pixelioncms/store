<?php if (!empty($summary)) :?>
<table class="table table-striped" id="debug-table">
    <thead>
        <tr>
            <th><?php echo YiiDebug::t('Query')?></th>
            <th><?php echo YiiDebug::t('Count')?></th>
            <th><?php echo YiiDebug::t('Total (s)')?></th>
            <th><?php echo YiiDebug::t('Avg. (s)')?></th>
            <th><?php echo YiiDebug::t('Min. (s)')?></th>
            <th><?php echo YiiDebug::t('Max. (s)')?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($summary as $id=>$entry){?>
        <?php
        if($entry[1]>$this->countLimit){
            $class = 'warning bg-warning';
        }elseif($entry[4]/$entry[1] > $this->timeLimit){
            $class = 'warning bg-danger text-white';
        }else{
            $class='';
        }

        ?>


        <tr class="<?php echo $class; ?>">
            <td><?php echo $entry[0]; ?></td>
            <td><span class="badge badge-secondary"><?php echo number_format($entry[1]); ?></span></td>
            <td><?php echo sprintf('%0.6F',$entry[4]); ?></td>
            <td><?php echo sprintf('%0.6F',$entry[4]/$entry[1]); ?></td>
            <td><?php echo sprintf('%0.6F',$entry[2]); ?></td>
            <td><?php echo sprintf('%0.6F',$entry[3]);?></td>
        </tr>
    <?php
    } ?>
    </tbody>
</table>
<?php else : ?>
<p>
    <?php echo YiiDebug::t('No SQL queries were recorded during this request or profiling the SQL is DISABLED.')?>
</p>
<?php endif; ?>