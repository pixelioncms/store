<?php

//namespace application\modules\admin\DefaultController;
Yii::import('mod.shop.models.orders.*');

class DefaultController extends AdminController
{

    private $_items;
    public $topButtons = false;
    public $desktop;

    public function actionIndex()
    {

        $this->topButtons = array(
            array(
                'label' => Yii::t('app', 'DESKTOP_CREATE'),
                'url' => array('/admin/desktop/create'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Desktop.*', 'Admin.Desktop.Update')),
                'htmlOptions' => array(
                    'class' => 'btn btn-success'
                )
            )
        );
        if (isset($_GET['d'])) {
            $desktopID = (int)$_GET['d'];
            $this->desktop = Desktop::model()->findByPk($desktopID);
            if (isset($this->desktop)) {
                if ($this->desktop->accessControlDesktop() || Yii::app()->user->isSuperuser) {
                    Yii::app()->session['desktop_id'] = $this->desktop->id;
                } else {

                    throw new CHttpException(401, 'Не найден рабочий стол');
                }
            } else {
                Yii::app()->db->createCommand()->insert(Desktop::model()->tableName(), array(
                    'name' => 'Рабочий стол',
                    'addons' => 1,
                    'columns' => 2,
                    'private' => 0,
                ));
                Yii::app()->session['desktop_id'] = 1;
            }
        } else {
            if (isset(Yii::app()->session['desktop_id'])) {
                $this->redirect(array('/admin?d=' . Yii::app()->session['desktop_id']));
            } else {
                $this->redirect(array('/admin?d=1'));
            }
        }

        $found = $this->findAddons();
        $items = CMap::mergeArray($this->_items, $found);
        $this->pageName = Yii::t('zii', 'Home');
        $this->breadcrumbs = array($this->pageName);
        $this->clearCache();
        $this->clearAssets();
        $this->render('index', array(
            //'ordersDataProvider'=>$this->getOrders(),
            'desktop' => $this->desktop,
            'AddonsItems' => $items
        ));
    }

    protected function findAddons()
    {
        $result = array();
        $modules = ModulesModel::getEnabled();
        foreach ($modules as $module) {
            $class = Yii::app()->getModule($module->name);
            if (isset($class->addonsArray)) {
                $result = CMap::mergeArray($result, $class->addonsArray['mainButtons']);
            }
        }
        return $result;
    }

    /**
     * Get latest orders
     *
     * @return ActiveDataProvider
     */
    public function getOrders()
    {
        Yii::import('mod.cart.models.Order');
        $cr = new CDbCriteria;
        $orders = Order::model()->search();
        $orders->setPagination(array('pageSize' => 10));
        $orders->setCriteria($cr);
        return $orders;
    }

    /**
     * Get orders date_create today
     *
     * @return ActiveDataProvider
     */
    public function getTodayOrders()
    {
        Yii::import('mod.cart.models.Order');
        $cr = new CDbCriteria;
        $cr->addCondition('date_create >= "' . date('Y-m-d 00:00:00') . '"');
        $dataProvider = Order::model()->search();
        $dataProvider->setCriteria($cr);
        return $dataProvider;
    }

    /**
     * Sum orders total price
     *
     * @return string
     */
    public function getOrdersTotalPrice()
    {
        $total = 0;
        foreach ($this->getTodayOrders()->getData() as $order)
            $total += $order->full_price;
        return Yii::app()->currency->number_format($total);
    }

    public function clearCache()
    {
        if (isset($_POST['cache_id'])) {
            //Yii::app()->cache->delete($_POST['cache_id']);
            Yii::app()->cache->flush();
            $this->setNotify(Yii::t('admin', 'SUCCESS_CLR_CACHE'));
            //$this->refresh();
        }
    }

    public function clearAssets()
    {
        if (isset($_POST['clear_assets'])) {
            FileSystem::fs('assets', Yii::getPathOfAlias('webroot'))->cleardir();
            $this->setNotify(Yii::t('admin', 'SUCCESS_CLR_ASSETS'));
            //$this->refresh();
        }
    }

    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => 'javascript:void(0)',
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Desktop.*', 'Admin.Desktop.Update', 'Admin.Desktop.CreateWidget', 'Admin.Desktop.Delete')),
                //'itemsHtmlOptions' => array('style' => 'width:220px'),
                'items' => array(
                    array(
                        'label' => Yii::t('app', 'DESKTOP_SETTINGS'),
                        'url' => array('/admin/desktop/update', 'id' => $this->desktop->id),
                        'icon' => Html::icon('icon-settings'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Desktop.*', 'Admin.Desktop.Update')),
                    ),
                    array(
                        'label' => Yii::t('app', 'DESKTOP_CREATE_WIDGET'),
                        'url' => array('/admin/desktop/createWidget', 'id' => $this->desktop->id),
                        'linkOptions' => array('id' => 'createWidget'),
                        'icon' => Html::icon('icon-add'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Desktop.*', 'Admin.Desktop.CreateWidget')),
                    ),
                    array(
                        'label' => Yii::t('app', 'DELETE'),
                        'url' => array('/admin/desktop/delete', 'id' => $this->desktop->id),
                        'icon' => Html::icon('icon-trashcan'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Desktop.*', 'Admin.Desktop.Delete')) && $this->desktop->id != 1,
                    ),
                )
            )
        );
    }

}
