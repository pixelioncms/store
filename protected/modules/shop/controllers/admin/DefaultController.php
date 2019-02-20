<?php

class DefaultController extends AdminController {

    public function actionIndex() {
        $this->topButtons=false;
        $this->render('index');
    }

    public function actionRefreshViews() {
        $model = ShopProduct::model()->updateAll(array('views' => 0), 'views > 1');
        if ($model) {
            $this->redirect(array('index'));
        } else {
            $this->redirect(array('index'));
        }
    }

    private function createHtmlTree($data) {
        $result = array();
        foreach ($data as $key=>$node) {
            
            $result[] = array(
                'id' => 'node_'.$node->id,
                'text' => Html::encode($node->name),
                'a_attr'=>array(
                  'class'=>'BALBAL'  
                ),
                'li_attr'=>array(
                    'data-switch'=>$node->switch,
                    'data-key'=>$key
                ),
                //"icon" => "icon-settings",
                'state' => array('opened' => ($node->id==1)?true:false, 'selected' => false),
                'children' => $this->createHtmlTree($node['children'])
            );
        }
        return $result;
    }

    /**
     * Example for jstree
     */
    public function actionAjaxRoot($id) {
        header('Content-type: application/json; charset=UTF-8');
        $d = ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk($id));
        echo CJSON::encode($this->createHtmlTree($d));
        die;
    }

}
