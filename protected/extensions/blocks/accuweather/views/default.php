<?php
if (!isset($result->hasError)) {
    echo $this->result[0]->WeatherText;
    echo $this->result[0]->WeatherIcon;
    echo round($this->result[0]->Temperature->Metric->Value);
print_r($this->result);
    ?>

    <div class="col-sm-6">
        <h1>123123123</h1>
    </div>
    <div class="col-sm-6">
        <h1>asd</h1>

    </div>
    <table class="table table-striped">

            <tr>
                <td><?= Yii::t('AccuweatherWidget.default', 'WIND') ?></td>
                <td>
                   ads</td>
            </tr>



            <tr>
                <td>123</td>
                <td>132%</td>
            </tr>


    </table>
<?php } else { ?>
    <div class="alert alert-warning">das
        <?php echo $result->message; ?>
    </div>
<?php } ?>
