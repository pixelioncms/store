<?php

/**
 * @author Troy <troytft@gmail.com>
 */
class SortableColumn extends CDataColumn {

    public $name = 'ordern';
    public $value = '';
    public $url = null;
    public $filter = false;
    private $_assetsPath;

    public function init() {
        if ($this->url == null)
            $this->url = '/' . preg_replace('#' . Yii::app()->controller->action->id . '$#', 'sortable', Yii::app()->controller->route);

        $this->registerScripts();
        parent::init();
    }

    public function registerScripts() {
        $name = "sortable_" . Yii::app()->controller->route;
        $id = $this->grid->getId();
        $this->_assetsPath = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->_assetsPath . '/styles.css');
        $cs->registerScriptFile($this->_assetsPath . '/sortable.js', CClientScript::POS_END);
        $cs->registerCoreScript('jquery.ui');
        $cs->registerScript("sortable-grid-{$id}-url", "
            var grid_sortable_url = '{$this->url}';
            ", CClientScript::POS_END);
        $cs->registerScript("sortable-grid-{$id}", "
            grid_sortable('{$id}');
            ", CClientScript::POS_END);
    }

    protected function renderDataCellContent($row, $data) {
        echo Html::tag('i', array('class'=>'icon-sort sortable-column-handler','style'=>'cursor: move;'), '', true);
    }

    protected function renderHeaderCellContent() {
        echo Html::tag('i', array('class'=>'icon-sort'), '', true);
    }

    public function renderDataCell($row) {
        $data = $this->grid->dataProvider->data[$row];
        $options = $this->htmlOptions;
        $options['class'] = 'sortable-column';
        $options['data-id'] = $data->primaryKey;
        echo Html::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo Html::closeTag('td');
    }

}
