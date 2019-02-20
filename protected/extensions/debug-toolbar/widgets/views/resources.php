<ul class="debug-dashboard">
    <?php foreach ($data as $entry) : ?>
        <li>
            <i class="<?php echo $entry['i'] ?>"></i>
            <em><?php echo $entry['value'] ?></em>
            <small><?php echo $entry['unit'] ?></small>
        </li>
    <?php endforeach; ?>
</ul>