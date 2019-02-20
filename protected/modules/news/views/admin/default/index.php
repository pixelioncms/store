
<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'enableHeader' => true,
    'name' => $this->pageName,
    'filter' => $model
));


/*
$this->widget('ext.adminList.AltGridView', array(
        'id' => 'testimonial-grid',
        'dataProvider' => $model->search(),
        'enableSorting' => false, // Drag & Drop ordering won't work if the column sorting is used!
        'filter' => $model, // Drag & Drop ordering on filtered grid will still work
        'columns' => array(
                        'title',

        ),
));
Yii::app()->clientScript->registerScript('sort_order', "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
                return ui;
        }; // Stops the table row being dragged from collapsing
        $('#testimonial-grid tbody').sortable({
                helper: fixHelper,
                update: function(event, ui) { 
                        var data = {'ids' : [], 'sort_orders' : []}; // Setup the post array
                        $(this).children('tr').each(function() {
                                data['ids'].push($(this).attr('data-record-id')); // Add the id values to the post array in order
                        });
                        $(this).children('tr').each(function() {
                                data['sort_orders'].push($(this).attr('data-sort-order')); // Add the sort_order values to the post array in order
                        });
                        data['sort_orders'].sort(); // Sort the sort_order values to represent the new order
                        $.post('" . $this->createUrl('reorder') . "', data); // Post to TestimonialController.php actionReOrder                                                                 
                }

        }).disableSelection();

");*/
/*
$this->widget('ext.sortable.SortableGridView', array(
        'id' => 'testimonial-grid',
        'dataProvider' => $model->search(),
    //'ajaxUpdate'=>true,
      //  'enableSorting' => false, // Drag & Drop ordering won't work if the column sorting is used!
        'filter' => $model, // Drag & Drop ordering on filtered grid will still work
        'columns' => array(
                        'title',

        ),
));*/