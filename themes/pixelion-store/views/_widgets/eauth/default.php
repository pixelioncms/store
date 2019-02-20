<div class="auth-services">

        <?php
        foreach ($services as $name => $service) { ?>

            <div class="btn-auth btn-auth-<?=$service->id?>">
                <?php
                echo CHtml::link($service->title, array($action, 'service' => $name), array(
                    'class' => 'btn d-block ',
                ));
                ?>
            </div>


       <?php }
        ?>

</div>


