<?php if(isset($result->attachmentsMain)){ ?>
<div id="brands-carousel" class="logo-slider wow fadeInUp">
    <div class="logo-slider-inner">	
        <div id="brand-slider" class="owl-carousel brand-slider custom-carousel owl-theme">
            <?php foreach ($result->attachmentsMain as $row) { ?>
                <div class="item">
                    <?php
                    echo Html::link(Html::image($row->getAttachmentImageUrl('manufacturer', '166x110'), $row->name), $row->getUrl(), array('class' => 'image'));
                    ?>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>


