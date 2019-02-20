
<div class="contact-information">
    <div class="module-heading">
        <h4 class="module-title">Информация</h4>
    </div><!-- /.module-heading -->

    <div class="module-body outer-top-xs">
        <ul class="toggle-footer" style="">
            <li class="media">
                <div class="pull-left">
                    <span class="icon fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="media-body">
                    <p><?= $model[0]->office->address; ?></p>
                </div>
            </li>

            <li class="media">
                <div class="pull-left">
                    <span class="icon fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-mobile fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="media-body">
                    <p><?= $model[0]->phones; ?></p>
                </div>
            </li>

            <li class="media">
                <div class="pull-left">
                    <span class="icon fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-envelope fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="media-body">
                    <span><?= $model[0]->email; ?></span><br>
                </div>
            </li>

        </ul>
    </div>
</div>
