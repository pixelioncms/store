<?php

if (!isset($result->hasError)) {

    ?>
    <script>
        $(function(){
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        })
    </script>
    <div class="col-sm-6">
        <h1><?= $result['name'] ?>, <?= $result['sys']['country'] ?></h1>
    </div>
    <div class="col-sm-6">
        <h1><?= $this->icon; ?><img style="display: none" src="<?= $this->assetsUrl ?>/images/<?= $result['weather'][0]['icon'] ?>.png" alt="" /> <?= floor($result['main']['temp']) ?><?= $this->deg ?> <br/><small><?= $result['weather'][0]['description'] ?></small></h1>

    </div>
    <table class="table table-striped">
        <?php if ($this->config->enable_wind) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'WIND') ?></td>
                <td>
                    <span data-toggle="tooltip" title="<?= $result['wind']['speed']; ?> м/с, <?= $this->degToCompass($result['wind']['deg']); ?>"><?= $this->degToCompassImage($result['wind']['deg']); ?></span>
                </td>
            </tr>
        <?php } ?>
        <?php if ($this->config->enable_pressure) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'PRESSURE') ?></td>
                <td><?= $result['main']['pressure'] ?> гПа</td>
            </tr>
        <?php } ?>
        <?php if ($this->config->enable_humidity) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'HUMIDITY') ?></td>
                <td><?= $result['main']['humidity'] ?>%</td>
            </tr>
        <?php } ?>
        <?php if ($this->config->enable_sunrise) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'SUNRISE') ?></td>
                <td><i class="w-sunrise" style="font-size:20px;"></i> <?php
                    $dateByZone = new DateTime(date('H:s', $result['sys']['sunrise']));
                    $dateByZone->setTimezone(new DateTimeZone(CMS::timezone()));
                    echo $dateByZone->format('H:i').' ('.CMS::timezone().')';
                    ?></td>
            </tr>
        <?php } ?>
        <?php if ($this->config->enable_sunset) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'SUNSET') ?></td>
                <td><i class="w-sunset" style="font-size:20px;"></i> <?php
                    $dateByZone = new DateTime(date('H:s', $result['sys']['sunset']));
                    $dateByZone->setTimezone(new DateTimeZone(CMS::timezone()));
                    echo $dateByZone->format('H:i').' ('.CMS::timezone().')';
                    ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <div class="alert alert-warning">
        <?php echo $result->message; ?>
    </div>
<?php } ?>
