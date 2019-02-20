<?php

class TemplateController extends AdminController {
    public $icon = 'icon-template';
    public $topButtons = false;

    public function allowedActions() {
        return 'ui, less, operation';
    }

    public function actionUi() {
        Yii::import('mod.admin.models.less.*');
        $theme = Yii::app()->settings->get('app', 'theme');
        if (isset($_POST['Less'])) {
            $path = Yii::getPathOfAlias("webroot.themes.{$theme}.less");

            Yii::import('app.phpless.lessc');
            $less = new lessc;
            $param = array();
            foreach ($_POST['Less'] as $key => $val) {
                $param[$key] = $val;
            }
            Yii::app()->settings->set('less', $param);
            // $less->setVariables($param);
            $less->compileFile($path . "/ui.less", Yii::getPathOfAlias("webroot.themes.{$theme}.assets.css") . "/ui-less.css");
        }
        $this->render('ui');
    }

    public function actionLess() {
        Yii::import('mod.admin.models.less.*');
        $theme = Yii::app()->settings->get('app', 'theme');
        $path = Yii::getPathOfAlias("webroot.themes.{$theme}.less");
        if (isset($_POST['Less'])) {


            Yii::import('app.phpless.lessc');
            $less = new lessc;
            $param = array();
            foreach ($_POST['Less'] as $key => $val) {
                $param[$key] = $val;
            }
            Yii::app()->settings->set('less', $param);
            $less->setVariables($param);
            /* $less->setVariables(array(
              'btn-default-bgcolor' => '#e0e0e0', //#e0e0e0
              'btn-primary-bgcolor' => '#265a88',
              'btn-success-bgcolor' => '#419641',
              'btn-info-bgcolor' => '#2aabd2',
              'btn-warning-bgcolor' => '#eb9316',
              'btn-danger-bgcolor' => '#c12e2a',
              )); */
            //$less->compileFile($path . "/buttons.less", Yii::getPathOfAlias("webroot.themes.{$theme}.assets.css") . "/buttons.css");
            $less->compileFile($path . "/buttons.less", Yii::getPathOfAlias("webroot.themes.{$theme}.less.css") . "/buttons.css");
        }


        if (isset($_GET['file'])) {
            $className = 'Less' . ucfirst(str_replace('.less', '', $_GET['file']));

            //Yii::import("webroot.themes.{$theme}.less.models.{$className}");
            // $model = new $className();
            // $fn = 'lessc.inc';
            // Yii::import("app.phpless.lessc");
            // $less = new lessc;
            if (isset($_POST['Less'])) {
                foreach ($_POST['Less'] as $key => $val) {
                    $param[$key] = $val;
                }

                Yii::app()->settings->set('less', $param);
                $less->setVariables($param);
            }

//require_once '[path to less.php]/Autoloader.php';
//Yii::import("app.phpless.lessc");
//Yii::import("app.phpless.lib.Less.Autoloader");
            require_once Yii::getPathOfAlias('vendor.phpless') . '/lessc.inc.php';
//Yii::import('vendor.phpless.vendor.autoload');
//require_once Yii::getPathOfAlias('vendor.phpless.lib.Less').'/Autoloader.php';
//Less_Autoloader::register();


            try {
                $parser = new Less_Parser();
                $parser->parse('@color: #4D926F; #header { color: @color; } h2 { color: @color; }');
                $css = $parser->getCss();
                echo $css;
            } catch (Exception $e) {
                die($e->getMessage());
            }
            /* $less->setVariables(array(
              'btn-default-bgcolor' => '#e0e0e0', //#e0e0e0
              'btn-primary-bgcolor' => '#265a88',
              'btn-success-bgcolor' => '#419641',
              'btn-info-bgcolor' => '#2aabd2',
              'btn-warning-bgcolor' => '#eb9316',
              'btn-danger-bgcolor' => '#c12e2a',
              ));

              $less->compileFile($path . '/'.$_GET['file'], Yii::getPathOfAlias("webroot.themes.{$theme}.less.css") .'/'. $_GET['file']);
             */
        }







        $this->render('less', array(
            'theme' => $theme,
            'form' => $model
                )
        );
    }

    public function actionOperation() {
        Yii::import('mod.admin.components.fs');
        if (isset($_GET['operation'])) {

            //$fs = new fs(dirname(__FILE__) . DS . 'data' . DS . 'root' . DS);
            $fs = new fs(Yii::getPathOfAlias('webroot.themes'));
            try {
                $rslt = null;
                switch ($_GET['operation']) {
                    case 'get_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->lst($node, (isset($_GET['id']) && $_GET['id'] === '#'));
                        break;
                    case "get_content":


                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->data($node);

                        break;
                    case 'create_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->create($node, isset($_GET['text']) ? $_GET['text'] : '', (!isset($_GET['type']) || $_GET['type'] !== 'file'));
                        break;
                    case 'rename_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->rename($node, isset($_GET['text']) ? $_GET['text'] : '');
                        break;
                    case 'delete_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->remove($node);
                        break;
                    case 'move_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? $_GET['parent'] : '/';
                        $rslt = $fs->move($node, $parn);
                        break;
                    case 'copy_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? $_GET['parent'] : '/';
                        $rslt = $fs->copy($node, $parn);
                        break;
                    default:
                        throw new Exception('Unsupported operation: ' . $_GET['operation']);
                        break;
                }
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($rslt);
            } catch (Exception $e) {
                header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
                header('Status:  500 Server Error');
                echo $e->getMessage();
            }
            Yii::app()->end();
        }
    }

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'TEMPLATE');
        $this->breadcrumbs = array(
            $this->pageName
        );
        if (isset($_POST['content'])) {
            if (!@file_put_contents(Yii::getPathOfAlias('webroot.themes') . DS . $_POST['file'], $_POST['content'])) {
                throw new CException(Yii::t('admin', 'Error write modules setting in {file}...', array('{file}' => $_POST['file'])));
            }
        }
        $this->render('index', array());
    }

    /*
      public function getAddonsMenu() {
      return array(
      array(
      'label' => Yii::t('admin', 'Шаблоны писем'),
      'url' => array('/admin/app/tplmail/index'),
      'icon' => 'icon-list',
      'visible' => Yii::app()->user->isSuperuser
      ),
      );
      }
     */
}
