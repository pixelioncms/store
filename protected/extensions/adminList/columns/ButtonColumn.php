<?php

/**
 * ButtonColumn class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList.columns
 * @uses CButtonColumn
 */
class ButtonColumn extends CButtonColumn {

    public $resetFilter = true;
    public $htmlOptions = array('class' => 'text-center','style'=>'width:160px');
    public $headerHtmlOptions = array('class' => 'button-column');
    public $footerHtmlOptions = array('class' => 'button-column');
    // public $template = '{view} {update} {delete} {switch}';
    public $viewButtonLabel;
    public $viewButtonIcon = 'icon-search';
    public $viewButtonUrl = 'Yii::app()->controller->createUrl("view",array("id"=>$data->primaryKey))';
    public $viewButtonOptions = array('class' => 'view'); //bDefault
    public $updateButtonLabel;
    public $updateButtonUrl = 'Yii::app()->controller->createUrl("update",array("id"=>$data->primaryKey))';
    public $updateButtonOptions = array('class' => 'update');
    public $updateButtonIcon = 'icon-edit';
    //public $fastupdateButtonLabel;
    //public $fastupdateButtonUrl = 'Yii::app()->controller->createUrl("update",array("id"=>$data->primaryKey))';
    //public $fastupdateButtonOptions = array('class' => 'update');
    //public $fastupdateButtonIcon = 'icon-pencil-3';
    public $switchButtonLabel;
    public $switchButtonUrl = 'Yii::app()->controller->createUrl("switch",array("model"=>$data->search()->modelClass, "id"=>$data->primaryKey, "switch"=>($data->switch)?0:1))';
    public $switchButtonIcon = 'icon-eye';
    public $switchButtonOptions = array('class' => 'switch');
    public $deleteButtonIcon = 'icon-delete';
    public $deleteButtonLabel;
    public $deleteButtonUrl = 'Yii::app()->controller->createUrl("delete",array("model"=>$data->search()->modelClass, "id"=>$data->primaryKey))';
    public $deleteButtonOptions = array('class' => 'delete');
    public $deleteConfirmation;

    public $afterDelete;
    public $hidden = array();

    public function init() {

        $this->initDefaultButtons();
        foreach ($this->buttons as $id => $button) {


            if (strpos($this->template, '{' . $id . '}') === false)
                unset($this->buttons[$id]);
            elseif (isset($button['click'])) {
                if (!isset($button['options']['class']))
                    $this->buttons[$id]['options']['class'] = $id;
                if (!($button['click'] instanceof CJavaScriptExpression))
                    $this->buttons[$id]['click'] = new CJavaScriptExpression($button['click']);
            }

        }
     
        $this->registerClientScript();
    }

    /**
     * Initializes the default buttons (view, update and delete).
     */
    protected function initDefaultButtons() {
        if ($this->header === null)
            $this->header = Yii::t('app', 'OPTIONS');
        if ($this->viewButtonLabel === null)
            $this->viewButtonLabel = Yii::t('app', 'VIEW');
        if ($this->updateButtonLabel === null)
            $this->updateButtonLabel = Yii::t('app', 'UPDATE', 1);
        if ($this->deleteButtonLabel === null)
            $this->deleteButtonLabel = Yii::t('app', 'DELETE');
        if ($this->switchButtonLabel === null)
            $this->switchButtonLabel = Yii::t('app', 'SWITCH');
        // if ($this->fastupdateButtonLabel === null)
        //     $this->fastupdateButtonLabel = Yii::t('app', 'dialog_update');
        if ($this->deleteConfirmation === null)
            $this->deleteConfirmation = Yii::t('app', 'YOU_ARE_CURE_DEL_ITEM');

        foreach (array('switch', 'view', 'update', 'delete') as $id) {

            $button = array(
                'label' => $this->{$id . 'ButtonLabel'},
                'url' => $this->{$id . 'ButtonUrl'},
                'icon' => $this->{$id . 'ButtonIcon'},
                'options' => $this->{$id . 'ButtonOptions'},
            );
            if (isset($this->buttons[$id]))
                $this->buttons[$id] = array_merge($button, $this->buttons[$id]);
            else
                $this->buttons[$id] = $button;
        }

        if ($this->afterDelete === null)
            $this->afterDelete = 'function(){}';
        if (!isset($this->buttons['switch']['click'])) {
            if (Yii::app()->request->enableCsrfValidation) {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            } else
                $csrf = '';

            $this->buttons['switch']['click'] = "function() {
                var th = this
                var afterDelete = $this->afterDelete;
                $('#{$this->grid->id}').yiiGridView('update', {
                    type: 'POST',
                    url: $(th).attr('href'),
                    dataType:'json',
                    data:{'$csrfTokenName':'$csrfToken',url:$(th).attr('href')},
                    success: function(data) {
                        if(data.success == true){
                            console.log(data);
                            $(th).attr('href',data.url);
                            if(data.value){
                                $(th).removeClass('btn-success').addClass('btn-outline-secondary');
                                $(th).find('i').removeClass('icon-eye').addClass('icon-eye-close');
                            }else{
                                $(th).removeClass('btn-outline-secondary').addClass('btn-success');
                                $(th).find('i').removeClass('icon-eye-close').addClass('icon-eye');
                            }
                        }
                        //$('#{$this->grid->id}').yiiGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function(XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });
                return false;
            }";
        }


        if (!isset($this->buttons['delete']['click'])) {

            if (Yii::app()->request->enableCsrfValidation) {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            } else
                $csrf = '';


            if (is_string($this->deleteConfirmation))
            // $confirmation = "if(!confirm(" . CJavaScript::encode($this->deleteConfirmation) . ")) return false;";
                $confirmation = "
                jQuery('#{$this->grid->id}').yiiGridView('confirm', {
                    message:" . CJavaScript::encode($this->deleteConfirmation) . ",
                    afterDelete:" . $this->afterDelete . ",
                    gridId:" . $this->grid->id . ",
                    uri:" . $this->buttons['delete']['url'] . ",
                    csrf:{ '$csrfTokenName':'$csrfToken' }
                        
}); return false;";

            else
                $confirmation = '';

            $this->buttons['delete']['click'] = "function() {
            var th = this
            

            
            $('body').append('<div id=\"dialog\"></div>');
            $('#dialog').dialog({
                modal: true,
                resizable: false,
                draggable: false,
                title:$(th).attr('title'),
                open:function(){
                    $(this).html(" . CJavaScript::encode($this->deleteConfirmation) . ");
                    $(th).parent().parent().parent().addClass('dsa');
                    console.log('das');
                },
                close: function (event, ui) {
                    $(this).remove();
                },
                buttons:[{
                        text:'OK',
                        'class':'btn btn-success btn-sm',
                        click:function(){
                            $(this).dialog('close');
                            var afterDelete = $this->afterDelete;
                            $('#{$this->grid->id}').yiiGridView('update', {
                                    type: 'POST',
                                    url: $(th).attr('href'),
                                    $csrf
                                    beforeSend: function(){
                                        $('#{$this->grid->id}').find('.grid-loading').addClass('loading');
                                    },
                                    complete: function () {
                                        $('#{$this->grid->id}').find('.grid-loading').removeClass('loading');
                                    },
                                    success: function(data) {
                                            $('#{$this->grid->id}').yiiGridView('update');
                                            afterDelete(th, true, data);
                                            
                                    },
                                    error: function(XHR) {
                                            return afterDelete(th, false, XHR);
                                    }
                            });
                        }
                    },{
                        text:common.message.cancel,
                        'class':'btn btn-secondary btn-sm',
                        click:function(){
                            $(this).dialog('close');
                            
                        }
                    }]
            });
            return false;
}";
        }
    }

    protected function renderDataCellContent($row, $data) {
        $hidden = array();
        $tr = array();

        $moduleID = ucfirst(Yii::app()->controller->module->id);
        $controllerID = ucfirst(str_replace('admin/', '', Yii::app()->controller->id));

        ob_start();
        echo CHtml::openTag('div', array('class' => 'btn-group'));

        if (!CMS::isModile()) {
            foreach ($this->buttons as $id => $button) {
                $actionID = ucfirst($id);
                if (Yii::app()->user->openAccess(array("{$moduleID}.{$controllerID}.*", "{$moduleID}.{$controllerID}.{$actionID}"))) {
                  $hiddenParam = "disallow_{$id}";

                    if (isset($data->$hiddenParam)) {
                        //if (in_array($data->primaryKey, $this->hidden[$id])) {
                        if (in_array($data->primaryKey, $data->$hiddenParam)) {
                            $hidden[] = "{" . $id . "}";
                        }
                    }

                    $this->renderButton($id, $button, $row, $data);
                }
                $tr['{' . $id . '}'] = ob_get_contents();

                ob_clean();
            }
        } else {
            ?>

            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-menu"></i> <span class="caret"></span>
            </button>

            <?php
            echo CHtml::openTag('ul', array('class' => 'dropdown-menu'));

            foreach ($this->buttons as $id => $button) {
                $actionID = ucfirst($id);
   
                if (Yii::app()->user->openAccess(array("{$moduleID}.{$controllerID}.*", "{$moduleID}.{$controllerID}.{$actionID}"))) {
                     $hiddenParam = "disallow_{$id}";
                    if (isset($data->$hiddenParam)) {
                        //if (in_array($data->primaryKey, $this->hidden[$id])) {
                        if (in_array($data->primaryKey, $data->$hiddenParam)) {
                            $hidden[] = "{" . $id . "}";
                        }
                    }

                    echo CHtml::openTag('li',array('class'=>'nav-item'));
                    $this->renderGroupButton($id, $button, $row, $data);
                    echo CHtml::closeTag('li');
                }
                $tr['{' . $id . '}'] = ob_get_contents();

                ob_clean();
            }
            echo CHtml::closeTag('ul');
        }
        echo CHtml::closeTag('div');
        ob_end_clean();

        echo strtr(str_replace($hidden, "", $this->template), $tr);
    }

    /**
     * Renders a link button.
     * @param string $id the ID of the button
     * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
     * See {@link buttons} for more details.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data object associated with the row
     */
    protected function renderButton($id, $button, $row, $data) {

        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data)))
            return;
        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row)) : '#';
        $options = isset($button['options']) ? $button['options'] : array();
        if (!isset($options['title']))
            $options['title'] = $label;

       
        if(isset($options['class'])){
            $options['class'] = $options['class'] . ' btn btn-outline-secondary' . Yii::app()->settings->get('app', 'btn_grid_size');
        }else{
            $options['class'] = ' btn btn-outline-secondary ' . Yii::app()->settings->get('app', 'btn_grid_size');
        }
        if (isset($button['icon']) && is_string($button['icon'])) {
            if ($id == 'switch') {
                if ($data->switch == 1) {
                    $button['icon'] = 'icon-eye';
                    $options['class'] = 'switch btn btn-success ' . Yii::app()->settings->get('app', 'btn_grid_size');
                } else {
                    $button['icon'] = 'icon-eye-close';
                    $options['class'] = 'switch btn btn-outline-secondary ' . Yii::app()->settings->get('app', 'btn_grid_size');
                }
            }
            echo CHtml::link(CHtml::openTag('i', array('class' => $button['icon'])) . '' . CHtml::closeTag('i'), $url, $options);
        } else {
            echo CHtml::link($label, $url, $options);
        }
    }

    public function renderHeaderCell() {
        $this->headerHtmlOptions['width'] = 'auto';
        parent::renderHeaderCell();
    }

    protected function renderGroupButton($id, $button, $row, $data) {

        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data)))
            return;
        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row)) : '#';
        $options = isset($button['options']) ? $button['options'] : array();
        if (!isset($options['title']))
            $options['title'] = $label;

        //$options['class'] = $options['class'];
        $options['class'] = 'nav-link';

        if (isset($button['icon']) && is_string($button['icon'])) {
            if ($id == 'switch') {
                if ($data->switch == 1) {
                    $button['icon'] = 'icon-eye';
                    $button['label'] = 'Скрыть';
                    $options['class'] .= ' switch-on';
                } else {
                    $button['icon'] = 'icon-eye-close';
                    $button['label'] = 'Показать';
                    $options['class'] .= ' switch-off';
                }
            }
            echo CHtml::link(CHtml::openTag('span', array('class' => $button['icon'])) . CHtml::closeTag('span') . ' ' . $button['label'], $url, array('class' => $options['class']));
        } else {
            echo CHtml::link($label, $url, $options);
        }
    }

    public function renderFilterCell() {
        if ($this->resetFilter) {
            $ajax = array(
                "success" => "function(data){
                    History.pushState(null, $('title').text(), '/" . Yii::app()->controller->route . "');
                }"
            );
            echo CHtml::openTag('td', array('class' => 'text-center'));
            echo CHtml::ajaxLink('<i class="icon-refresh"></i>', '/' . Yii::app()->controller->route, $ajax, array('class' => 'refresh-filter btn btn-secondary _btn-xs', 'id' => 'resetGridFilter', 'title' => Yii::t('GridView.default', 'REFRESH_FILTER')));
            echo CHtml::closeTag('td');
        } else {
            parent::renderFilterCell();
        }
    }

    protected function registerClientScript() {
        if (CMS::isModile()) {

            Yii::app()->getClientScript()->registerScriptFile($this->grid->baseScriptUrl . "/bs-responsive-table-dropdown.min.js");
        }
        return parent::registerClientScript();
        /* $js = array();
          foreach ($this->buttons as $id => $button) {
          if (isset($button['click'])) {
          $function = CJavaScript::encode($button['click']);
          $class = preg_replace('/\s+/', '.', $button['options']['class']);
          $js[] = "jQuery(document).on('click','#{$this->grid->id} a.{$class}',$function);";
          }
          }

          if ($js !== array())
          Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id, implode("\n", $js)); */
    }

}
