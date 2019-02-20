<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript">

    $(function(){
        var sel_list = '.field_mapTools_top, .field_mapTools_left';
        $('#ContactsMaps_mapTools').change(function(){
            appcommon.hasChecked('#ContactsMaps_mapTools', sel_list);
        });
        common.hasChecked('#ContactsMaps_mapTools', sel_list);
        
        var zoomControl_list = '.field_zoomControl_top, .field_zoomControl_left, .field_zoomControl_right';
        $('#ContactsMaps_zoomControl').change(function(){
            common.hasChecked('#ContactsMaps_zoomControl', zoomControl_list);
        });
        common.hasChecked('#ContactsMaps_zoomControl', zoomControl_list);
    });
    

</script>