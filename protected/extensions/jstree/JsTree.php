<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * $this->widget('ext.jstree.JsTree',array('data'=>'ARRAY_TRE', 'options'=>'ARRAY_OPTIONS'));
 * </code>
 * 
 * @package widgets
 * @uses CWidget
 */
class JsTree extends CWidget {

    /**
     * @var string Id of elements
     */
    public $id;

    /**
     * @var array of nodes. Each node must contain next attributes:
     *  id - If of node
     *  name - Name of none
     *  hasChildren - boolean has node children
     *  children - get children array
     */
    public $data = array();
    //public $selected=array(3,4);
    /**
     * @var array jstree options
     */
    public $options = array();

    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * Init widget
     */
    public function init() {
        $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $this->cs = Yii::app()->getClientScript();
        $this->cs->registerCoreScript('cookie');
        $this->cs->registerScriptFile($assetsUrl . '/jstree.min.js',CClientScript::POS_END);
        $this->cs->registerCssFile($assetsUrl . '/themes/default/style.min.css');
        $this->cs->registerCssFile($assetsUrl . '/dashboard.css');
    }

    public function run() {
        echo Html::openTag('div', array(
            'id' => $this->id,
        ));

        echo Html::closeTag('div');

        $this->options['core']['data'] = $this->createHtmlTree($this->data);
        $options = CJavaScript::encode($this->options);
        // print_r($this->options['core']['data']);
        $this->cs->registerScript('JsTreeScript', "
			$('#{$this->id}').jstree({$options});
        ",CClientScript::POS_END);
    }

    private function createHtmlTree($data) {
        $result = array();
        foreach ($data as $node) {
            /* $result['id']='node_' . $node['id'];
              $result['text']=Html::encode($node->name);
              $result['icon']=($node['switch'])?'icon-eye':'icon-eye-close';
              $result['state']=array(
              'opened' => ($node->id == 1) ? true : false,
              'switch'=>$node['switch']
              );
              $result['children']=$this->createHtmlTree($node['children']); */

            if (Yii::app()->controller->id == 'admin/category' || Yii::app()->controller->id == 'admin/default') {
                $icon = ($node['switch']) ? 'icon-eye' : 'icon-eye-close';
            } else {
                $icon = '';
            }
          //  $visible = (isset($node->visible)) ? $node->visible : true;
           // if ($visible) {
                $result[] = array(
                    'id' => 'node_' . $node['id'],
                    'text' => Html::encode($node->name),
                    'icon' => $icon,
                    'state' => array(
                        'opened' => ($node->id == 1) ? true : false,
                        'switch' => $node['switch'],
                    //'selected' => (in_array($node->id, $this->selected)) ? true : false
                    ),
                    'children' => $this->createHtmlTree($node['children'])
                );
          //  }
        }
        return $result;
    }

    /**
     * Create ul html tree from data array
     * @param string $data
     */
    private function createHtmlTree2($data) {
        echo Html::openTag('ul');
        foreach ($data as $node) {

            echo Html::openTag('li', array(
                'id' => $this->id . 'Node_' . $node['id'],
                'data-status' => $node['switch'],
                'class' => ($node['switch']) ? '' : 'hiddenClass'
            ));
            echo Html::link(Html::encode($node->name));
            if ($node['hasChildren'] === true) {
                // echo Html::openTag('ul');
                $this->createHtmlTree($node['children']);
                // echo Html::closeTag('ul');
            }
            echo Html::closeTag('li');
        }
        echo Html::closeTag('ul');
    }

}
