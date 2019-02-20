 <?php
$colors=array(
    CLogger::LEVEL_PROFILE=>'#DFFFE0',
    CLogger::LEVEL_INFO=>'badge-info',
    CLogger::LEVEL_WARNING=>'badge-warning',
    CLogger::LEVEL_ERROR=>'badge-danger',
    CLogger::LEVEL_TRACE=>'badge-secondary'
);

?>

<div data-ydtb-panel-data="<?php echo $this->id ?>">
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo YiiDebug::t('Message (details)')?></th>
                    <th><?php echo YiiDebug::t('Level')?></th>
                    <th><?php echo YiiDebug::t('Category')?></th>
                    <th><?php echo YiiDebug::t('Time')?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($logs as $id=>$entry){  ?>
                <tr>
                    <td><?php echo nl2br($entry[0]) ?></td>
                    <td><span class="badge <?php echo $colors[$entry[1]]; ?>"><?php echo $entry[1]; ?></span></td>
                    <td><?php echo $entry[2] ?></td>
                    <td><?php echo date('H:i:s.',$entry[3]).sprintf('%06d',(int)(($entry[3]-(int)$entry[3])*1000000));?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

