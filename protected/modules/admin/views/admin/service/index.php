<?php
if (true) {
    //  Yii::app()->tpl->alert('info','Сервис в разработке!',false);
    //  return;
}
$data = LicenseCMS::run()->getData();

?>
<div class="row">
    <div class="col-lg-6">
        <?php
        if (isset($data['support'])) {
            Yii::app()->tpl->openWidget(array('title' => 'Контактная информация'));
            ?>
            <div class="list-group">
                <?php
                foreach ($data['support']['members'] as $members) {
                    ?>
                    <div class="list-group-item">
                        <i class="icon-user"></i>
                        <?= $members['name'] ?> <?php if (isset($members['place'])) { ?>( <?= $members['place'] ?>)<?php } ?>
                    </div>
                    <?php if (isset($members['contact']['phone'])) { ?>
                        <div class="list-group-item">
                            <div><i class="icon-phone"></i> <?= $members['contact']['phone'] ?></div>
                        </div>
                    <?php } ?>
                    <?php if (isset($members['contact']['email'])) { ?>
                        <div class="list-group-item">
                            <div><i class="icon-envelope"></i> <?= $members['contact']['email'] ?></div>
                        </div>
                    <?php } ?>

                <?php } ?>
                <div class="list-group-item">
                    <div><i class="icon-location"></i> <?= $data['support']['address'] ?></div>
                </div>
            </div>



            <?php
            Yii::app()->tpl->closeWidget();
        }

        if (isset($data['news']) && count($data['news']) > 0) {
            Yii::app()->tpl->openWidget(array('title' => 'Новости'));

            foreach ($data['news'] as $news) {
                echo $news['title'];
            }

            Yii::app()->tpl->closeWidget();
        }
        ?>
    </div>
    <div class="col-lg-6">
        <?php
        /* if (isset($providerProffers)) {
          Yii::app()->tpl->openWidget(array('title' => 'Предложения'));
          $this->widget('ext.adminList.GridView', array(
          'dataProvider' => $providerProffers,
          'selectableRows' => false,
          'enableHeader' => false,
          'autoColumns' => false,
          'enablePagination' => true,
          'columns' => array(
          array(
          'name' => 'title',
          'header' => 'Название файла',
          'type' => 'html',
          // 'value' => '$data->title',
          'htmlOptions' => array('class' => 'text-left'),
          ),
          )
          )
          );
          Yii::app()->tpl->closeWidget();
          } */

        Yii::app()->tpl->openWidget(array('title' => 'Написать тех поддержки'));
        echo new CMSForm($supportForm->config, $supportForm);
        Yii::app()->tpl->closeWidget();
        ?>
    </div>
</div>
