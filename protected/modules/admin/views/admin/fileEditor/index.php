<?php
/**
 *
 * @copyright (c) 2018, Semenov Andrew
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @author Semenov Andrew <info@andrix.com.ua>
 *
 * @link http://pixelion.com.ua PIXELION CMS
 * @link http://andrix.com.ua Developer
 *
 */


$robotsmess = CMS::isChmod($this->path_robots, 0666);
if ($robotsmess)
    Yii::app()->tpl->alert('warning', Yii::t('app', 'CHMOD_ERROR', array('{dir}' => $this->path_robots, '{chmod}' => 666)), false);
$htaccessmess = CMS::isChmod($this->path_htaccess, 0666);
if ($htaccessmess)
    Yii::app()->tpl->alert('warning', Yii::t('app', 'CHMOD_ERROR', array('{dir}' => $this->path_htaccess, '{chmod}' => 666)), false);

if (!$robotsmess && !$htaccessmess) {
    Yii::app()->tpl->openWidget(array('title' => $this->pageName));
    echo Html::form();
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>.htaccess</h3>
                <?= Html::textArea('htaccess', $htaccess, array('class' => 'form-control', 'rows' => 20, 'style' => 'resize:none;')); ?>
            </div>
            <div class="col-sm-6">
                <h3>robots.txt</h3>
                <?= Html::textArea('robots', $robots, array('class' => 'form-control', 'rows' => 20, 'style' => 'resize:none;')); ?>
            </div>
        </div>
    </div>
    <div class="form-group row text-center">
        <div class="col-sm-12">
            <?php
            echo Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success'));
            ?>
        </div>
    </div>
    <?php
    echo Html::endForm();
}
Yii::app()->tpl->closeWidget();







$ip2 = '195.78.247.104';
$ip2= '188.191.237.131';

$ip2 = '109.252.111.66';
$ip2 = '8.8.8.8';
$ip2 = '2001:4d80::60:805:200:0:107a';
$ip2 = '2001:67c:2dfc:fff1:f50c:75a2:b6b2:6ee5';
$ip2 = '2402:bc07:10d5:d700:e831:4bc3:247c:f31e';
$ip2='37.18.42.81';

$geo = Yii::app()->geoip->get($ip2);

echo $geo->city;
echo '<br>';
echo $geo->region;
echo '<br>';
echo $geo->country;
echo '<br>';
echo $geo->countryCode;
echo '<br>';
echo $geo->location->lat.' - '.$geo->location->lng;
echo '<br>';
echo $geo->hostname;
echo '<br>';
echo $geo->postal;
echo '<br>';
echo $geo->org;
echo '<br>';
echo $geo->phone;


//$geo->get($ip2)->location->lat
//$geo->get($ip2)->city
//print_r($geo->result);













