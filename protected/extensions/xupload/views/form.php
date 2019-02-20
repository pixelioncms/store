<!-- The file upload form used as target for the file upload widget -->
<?php if ($this->showForm) echo CHtml::beginForm($this->url, 'post', $this->htmlOptions); ?>
<div class="row fileupload-buttonbar">
    <div class="span7">
        <!-- The fileinput-button span is used to style the file input field as button -->
        <div  style="margin: 30px auto 0 auto; text-align:center;">
        <span class="btn btn-primary fileinput-button">
            <i class="icon-upload"></i>
            <span><?php echo $this->t('1#Add files|0#Choose file', $this->multiple); ?></span>
            <?php
            if ($this->hasModel()) :
                echo CHtml::activeFileField($this->model, $this->attribute, $htmlOptions) . "\n";
            else :
                echo CHtml::fileField($name, $this->value, $htmlOptions) . "\n";
            endif;
            ?>
        </span>
            </div>
        <?php if ($this->multiple && !$this->autoUpload) { ?>
            <button type="submit" class="btn btn-primary start">
                <i class="icon-upload"></i>
                <span>Start upload</span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="icon-cancel-circle"></i>
                <span>Cancel upload</span>
            </button>
            <button type="button" class="btn btn-danger delete">
                <i class="icon-trashcan"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" class="toggle">
        <?php } ?>
    </div>
    <?php if (!$this->autoUpload) { ?>

    <div class="span5123">
        <!-- The global progress bar -->
        <div class="progress progress-success progress-striped active fade contentProgress ">
            <div class="bar barG" style="width:0%;"></div>
        </div>
    </div>
     <?php } ?>
</div>
<!-- The loading indicator is shown during image processing -->
<div class="fileupload-loading"></div>
<br>
<!-- The table listing the files available for upload/download -->
<table class="table table-striped table-bordered">
    <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
</table>
<?php if ($this->showForm) echo CHtml::endForm(); ?>
