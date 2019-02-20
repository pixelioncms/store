<?php
$this->widget('mod.shop.widgets.jstree.JsTree', array(
    'id' => 'CategoryAssignTreeDialog',
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->published()->findAllByPk(1)),
));

?>
<script type="text/javascript">
    $("#CategoryAssignTreeDialog").jstree(<?=
CJavaScript::encode(array(
    'core' => array(
        // Open root
        'initially_open' => 'CategoryAssignTreeDialogNode_1',
    ),
    'plugins' => array('themes', 'html_data', 'ui', 'crrm', 'search', 'checkbox', 'cookies'),

    'checkbox' => array(
        'two_state' => true,
    ),
    'cookies' => array(
        'save_selected' => true,
    )
))
?>);
</script>
