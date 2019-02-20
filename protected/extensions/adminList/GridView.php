<?php

Yii::import('zii.widgets.grid.CGridView');
Yii::import('ext.adminList.columns.*');

/**
 * GridView class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList
 * @uses CGridView
 */
class GridView extends CGridView {

    public $itemsCssClass = 'table table-striped table-bordered';
    public $headerOptions = true;
    public $autoColumns = true;
    public $enableHeader = true;
    public $headerButtons = array();
    public $name;
    public $rowCssStyleExpression;
    public $template = '{items}';
    public $selectableRows = 2;

    /**
     * @var array List of custom actions to display in footer.
     * See example in {@link GridView::getFooterActions}
     */
    protected $_customActions;

    /**
     * @var bool Set to false to hide `Delete` button.
     */
    public $hasDeleteAction = true;

    /**
     * @var bool Display custom actions
     */
    public $enableCustomActions = true;
    public $enableHistory = true;
    public $genId = true;
    public $pagerCssClass = 'page-container';

    public function run() {
        $this->registerClientScript();

        echo Html::openTag($this->tagName, $this->htmlOptions) . "\n";
        echo Html::openTag('div', array('class' => 'grid-loading'));
        echo Html::closeTag('div');
        $this->renderContent();

        if (!($this->dataProvider instanceof CArrayDataProvider)) {
            $this->renderKeys();
        }
        echo Html::closeTag($this->tagName);
    }

    /**
     * Initializes the grid view.
     */
    public function init() {
        // Uhhhh! ugly copy-paste from CBaseListView::init()!
        if ($this->dataProvider === null)
            throw new CException(Yii::t('zii', 'The "dataProvider" property cannot be empty.'));

        // $this->dataProvider->getData();

        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = 'grid-view';

        if ($this->enableSorting && $this->dataProvider->getSort() === false)
            $this->enableSorting = false;
        if ($this->enablePagination && $this->dataProvider->getPagination() === false)
            $this->enablePagination = false;
        // End of ugly

        if ($this->baseScriptUrl === null) {
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(
                    dirname(__FILE__) . DS . 'assets', false, -1, YII_DEBUG
            );
        }

        if ($this->cssFile !== false) {
            if ($this->cssFile === null)
                $this->cssFile = $this->baseScriptUrl . '/styles.css';
            Yii::app()->getClientScript()->registerCssFile($this->cssFile);
        }




        $this->pager = array(
            //'cssFile' => $this->baseScriptUrl . '/pager.css',
            'class' => 'LinkPager',
            'cssFile' => false,
            'htmlOptions' => array('class' => 'pagination justify-content-center'),
            'header' => (Yii::app()->controller instanceof AdminController) ? '' : null,
            'nextPageLabel' => CHtml::tag('i', array('class' => 'icon-arrow-right'), '', true),
            'prevPageLabel' => CHtml::tag('i', array('class' => 'icon-arrow-left'), '', true),
            'firstPageLabel' => CHtml::tag('i', array('class' => 'icon-arrow-first'), '', true),
            'lastPageLabel' => CHtml::tag('i', array('class' => 'icon-arrow-last'), '', true),
            'maxButtonCount' => (CMS::isModile()) ? 0 : 10,
                //'pageSize'=>Yii::app()->settings->get('app', 'pagenum')
        );


        if ((isset($this->dataProvider->model)) && $this->autoColumns) {  //@remove isset($this->dataProvider->model->gridColumns) && 
            $cr = new CDbCriteria;
            $cr->order = '`t`.`ordern` ASC';
            $cr->condition = '`t`.`grid_id`=:grid';
            $cr->params = array(
                'grid' => $this->id
            );
            $model = GridColumns::model()->findAll($cr);
            $colms = array();
            
            if (isset($model)) {
                foreach ($model as $k => $col) {
                    $colms[$col->column_key] = $col->column_key;
                }
            }


            $this->columns = $this->dataProvider->model->getColumnSearch($colms);
        }
        $this->initColumns();
    }

    protected function createDataColumn($text) {
        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches))
            throw new CException(Yii::t('zii', 'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
        $column = new DataColumn($this);
        $column->name = $matches[1];
        if (isset($matches[3]) && $matches[3] !== '')
            $column->type = $matches[3];
        if (isset($matches[5]))
            $column->header = $matches[5];
        return $column;
    }

    protected function initColumns() {
        $mycolumns = array();
        if ($this->columns === array()) {
            if ($this->dataProvider instanceof ActiveDataProvider){
                $this->columns = $this->dataProvider->model->attributeNames();
             
            }
            elseif ($this->dataProvider instanceof IDataProvider) {
                // use the keys of the first row of data as the default columns
                $data = $this->dataProvider->getData();
                if (isset($data[0]) && is_array($data[0]))
                    $this->columns = array_keys($data[0]);
            }
        }
        $id = $this->getId();

        foreach ($this->columns as $i => $column) {
     
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                if (!isset($column['class'])) {
                    $column['class'] = 'DataColumn';
                }
                if (isset($column['class'])) {
                    if ($column['class'] == 'EditableColumn') {
                        $this->afterAjaxUpdate = new CJavaScriptExpression('js:function(){$(".editable").editable();}');
                    } elseif ($column['class'] == 'ext.sortable.SortableColumn') {
                        $this->afterAjaxUpdate = new CJavaScriptExpression('js:function(){grid_sortable("' . $this->id . '");}');
                    }
                }
                $column = Yii::createComponent($column, $this);
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            if ($column->id === null)
                $column->id = $id . '_c' . $i;
            $this->columns[$i] = $column;
        }

        foreach ($this->columns as $column){
           
            $column->init();
        }
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript() {
        $id = $this->getId();

        if ($this->ajaxUpdate === false)
            $ajaxUpdate = false;
        else
            $ajaxUpdate = array_unique(preg_split('/\s*,\s*/', $this->ajaxUpdate . ',' . $id, -1, PREG_SPLIT_NO_EMPTY));
        $options = array(
            'ajaxUpdate' => $ajaxUpdate,
            'ajaxVar' => $this->ajaxVar,
            'pagerClass' => $this->pagerCssClass,
            'loadingClass' => $this->loadingCssClass,
            'filterClass' => $this->filterCssClass,
            'tableClass' => $this->itemsCssClass,
            'selectableRows' => $this->selectableRows,
            'enableHistory' => $this->enableHistory,
            'updateSelector' => $this->updateSelector,
            'filterSelector' => $this->filterSelector
        );
        if ($this->ajaxUrl !== null)
            $options['url'] = CHtml::normalizeUrl($this->ajaxUrl);
        if ($this->ajaxType !== null) {
            $options['ajaxType'] = strtoupper($this->ajaxType);
            $request = Yii::app()->getRequest();
            if ($options['ajaxType'] == 'POST' && $request->enableCsrfValidation) {
                $options['csrfTokenName'] = $request->csrfTokenName;
                $options['csrfToken'] = $request->getCsrfToken();
            }
        }

        if ($this->enablePagination)
            $options['pageVar'] = $this->dataProvider->getPagination()->pageVar;
        foreach (array('beforeAjaxUpdate', 'afterAjaxUpdate', 'ajaxUpdateError', 'selectionChanged') as $event) {
            if ($this->$event !== null) {
                if ($this->$event instanceof CJavaScriptExpression)
                    $options[$event] = $this->$event;
                else
                    $options[$event] = new CJavaScriptExpression($this->$event);
            }
        }

        $options = CJavaScript::encode($options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bbq');
        if ($this->enableHistory && !Yii::app()->request->isAjaxRequest)
            $cs->registerCoreScript('history');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.yiigridview.js', CClientScript::POS_END);
        $cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#$id').yiiGridView($options);");

        //  parent::registerClientScript();

        if (isset($this->dataProvider->model) && isset($this->autoColumns))
            $cs->registerScriptFile($this->baseScriptUrl . '/editgridcolums.js', CClientScript::POS_END);

        if (Yii::app()->request->isAjaxRequest) {
            $cs->scriptMap = array(
               // 'editable.js'=>true
                    //  'jquery.yiigridview.js'=>false,
                    //  'jquery.js' => false,
                    //  'jquery.min.js' => false,
                    // 'editgridcolums.js'=>false,
                    //      'jquery.ba-bbq.js'=>false,
                    // 'jquery.ba-bbq.min.js'=>false,
                    // 'jquery.history.js'=>false,
                    //'jquery.jgrowl.js'=>false,
            );
        }
    }

    protected static function t($message, $params = array()) {
        return Yii::t('GridView.default', $message, $params);
    }

    /**
     * Renders the data items for the grid view.
     */
    public function renderItems() {
        if ($this->enableHeader) { //$this->enableHeader
            $params = array();
            if (isset($this->name))
                $params['title'] = $this->name;
            if ($this->headerOptions && $this->autoColumns) {
                $params['options'] = array(
                    array(
                        'label' => self::t('CHANGE_TABLE'),
                        'icon' => 'icon-grid',
                        'htmlOptions' => array('onClick' => 'return grid.editcolums("' . $this->id . '","' . $this->dataProvider->modelClass . '","' . $this->controller->module->id . '");','class'=>'nav-link'),
                        //   'href' => 'javascript:grid.editcolums("' . $this->id . '","' . $this->dataProvider->modelClass . '","' . $this->controller->module->id . '");'
                        'href' => 'javascript:void(0);'
                    )
                );
            }
            if (isset($this->headerButtons))
                $params['buttons'] = $this->headerButtons;
            Yii::app()->tpl->openWidget($params);
        }


        echo Html::openTag('div', array('class' => 'table-responsive'));
        parent::renderItems();
        echo Html::closeTag('div');
        if ($this->selectableRows > 0 && $this->enableCustomActions === true && (count($this->dataProvider->getData()) > 0)) {
            //echo '<select class="CA" name="test" onChange="customActions(this);">';
            if ($this->enableCustomActions === true) {

                $this->widget('ext.adminList.GridCheckboxDropDown', array(
                    'id' => $this->getId() . 'Actions',
                    'encodeLabel' => false,
                    'submenuHtmlOptions' => array('class' => 'dropdown-menu'),
                    'htmlOptions' => array(
                        'class' => 'btn-group dropup gridActions',
                    ),
                    'items' => array(
                        array(
                            'label' => self::t('CHECKED_SELECT_NAV'),
                            'url' => 'javascript:void(0)',

                            'linkOptions' => array(
                                'class' => 'btn btn-sm btn-secondary dropdown-toggle',
                                'data-toggle' => 'dropdown',
                                'aria-haspopup' => "true",
                                'aria-expanded' => "false"
                            ),
                            'items' => $this->getCustomActions()
                        ),
                    )//this->getCustomActions()
                ));
            }
        }
        $this->renderPager();
        if ($this->enableHeader) //$this->autoColumns && 
            Yii::app()->tpl->closeWidget();
    }

    public function setCustomActions($actions) {
        foreach ($actions as $action) {
            if (!isset($action['linkOptions']))
                $action['linkOptions'] = $this->getDefaultActionOptions();
            else
                $action['linkOptions'] = array_merge($this->getDefaultActionOptions(), $action['linkOptions']);
            $this->_customActions[] = $action;
        }
    }

    public function getCustomActions() {

        $moduleID = ucfirst(Yii::app()->controller->module->id);
        $controllerID = ucfirst(str_replace('admin/', '', Yii::app()->controller->id));

        if ($this->hasDeleteAction === true) {
            if (Yii::app()->user->checkAccess("{$moduleID}.{$controllerID}.*") || Yii::app()->user->checkAccess("{$moduleID}.{$controllerID}.Delete")) {
                $this->customActions = array(array(
                        'label' => Yii::t('app', 'DELETE'),
                        'url' => $this->owner->createUrl('delete'),
                        'icon' => 'icon-delete',
                        'htmlOptions'=>array('class'=>'nav-item'),
                        'linkOptions' => array(
                            'class' => 'nav-link actionDelete',
                            'data-question' => Yii::t('app', 'PERFORM_ACTION'),
                        )
                ));
            }
        }

        return $this->_customActions;
    }

    /**
     * @return array Default linkOptions for footer action.
     */
    public function getDefaultActionOptions() {
        return array(
            //'data-token' => Yii::app()->request->csrfToken,
            'data-question' => Yii::t('app', 'PERFORM_ACTION'),
            'model' => $this->dataProvider->modelClass,
            'onClick' => strtr('return $.fn.yiiGridView.runAction(":grid", this);', array(
                ':grid' => $this->getId()
                    )
            ),
        );
    }

    public function renderEmptyText() {
        $emptyText = $this->emptyText === null ? Yii::t('app', 'NO_INFO') : $this->emptyText;
        Yii::app()->tpl->alert('info', $emptyText, false);
    }

    public function getId($autoGenerate = true) {
        if (isset($this->dataProvider->modelClass) && $this->genId) {
            return strtolower($this->dataProvider->modelClass) . '-grid';
        } else {
            return parent::getId($autoGenerate);
        }
    }

}
