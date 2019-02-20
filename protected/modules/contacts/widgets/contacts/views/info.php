<div class="col-md-3 contact-info">
    <div class="contact-title">
        <h4>Информация</h4>
    </div>
    <div class="clearfix address">
        <span class="contact-i"><i class="fa fa-map-marker"></i></span>
        <span class="contact-span"><?= $model[0]->office->address; ?></span>
    </div>
    <div class="clearfix phone-no">
        <span class="contact-i"><i class="fa fa-mobile"></i></span>
        <span class="contact-span"><?= $model[0]->phones; ?></span>
    </div>
    <div class="clearfix email">
        <span class="contact-i"><i class="fa fa-envelope"></i></span>
        <span class="contact-span"><?= $model[0]->email; ?></span>
    </div>
</div>

