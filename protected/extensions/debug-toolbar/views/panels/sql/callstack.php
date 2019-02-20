<?php if (!empty($callstack)) :?>
<table class="table table-striped" id="debug-table">
    <thead>
        <tr>
            <th>#</th>
            <th><?php echo YiiDebug::t('Query')?></th>
            <th nowrap="nowrap"><?php echo YiiDebug::t('Time (s)')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($callstack as $id=>$entry):?>
            <?php
            if($entry[1]>$this->timeLimit){
                $class = 'warning bg-warning';
            }else{
                $class='';
            }
            ?>
        <tr class="<?php echo $class; ?>">
            <td><?php echo $id; ?></td>
            <td><?php echo $entry[0]; ?></td>
            <td><?php echo sprintf('%0.6F',$entry[1]); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
<p>
    <?php echo YiiDebug::t('No SQL queries were recorded during this request or profiling the SQL is DISABLED.')?>
</p>
<?php endif; ?>