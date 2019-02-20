<?php
$now = time();
?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab<?= $now; ?>" data-toggle="tab" href="#tab-home<?= $now; ?>" role="tab" aria-controls="tab-home<?= $now; ?>" aria-selected="true">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab<?= $now; ?>" data-toggle="tab" href="#tab-profile<?= $now; ?>" role="tab" aria-controls="tab-profile<?= $now; ?>" aria-selected="false">Profile</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab<?= $now; ?>" data-toggle="tab" href="#tab-contact<?= $now; ?>" role="tab" aria-controls="tab-contact<?= $now; ?>" aria-selected="false">Contact</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="tab-home<?= $now; ?>" role="tabpanel" aria-labelledby="home-tab">123</div>
  <div class="tab-pane fade" id="tab-profile<?= $now; ?>" role="tabpanel" aria-labelledby="profile-tab">456</div>
  <div class="tab-pane fade" id="tab-contact<?= $now; ?>" role="tabpanel" aria-labelledby="contact-tab">678<div>
</div>