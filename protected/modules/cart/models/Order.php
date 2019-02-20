<?php

/**
 * This is the model class for table "Order".
 *
 * The followings are the available columns in table 'Order':
 * @property integer $id
 * @property integer $user_id
 * @property string $secret_key
 * @property integer $delivery_id
 * @property integer $payment_id
 * @property float $delivery_price
 * @property float $total_price Sum of ordered products
 * @property float $full_price Total price + delivery price
 * @property integer $status_id
 * @property integer $paid
 * @property string $user_name
 * @property string $user_email
 * @property string $user_address
 * @property string $user_phone
 * @property string $user_comment
 * @property string $admin_comment
 * @property string $ip_create
 * @property string $date_create
 * @property string $date_update
 * @property string $discount
 */
Yii::import('mod.shop.ShopModule');
Yii::import('app.traits.Trait_Petrovich');

class Order extends ActiveRecord
{

    use Trait_Petrovich;

    protected $status_state;
    public $status_id = 1;
    const MODULE_ID = 'cart';

    public function getGridColumns()
    {
        Yii::import('mod.shop.components.ProductsPreviewColumn');
        Yii::import('mod.cart.models.*');
        return array(
            array(
                'name' => 'id',
                'type' => 'raw',
                'value' => '$data->id',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'user_name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->user_name), array("/admin/cart/default/update", "id"=>$data->id))',
            ),
            array(
                'class' => 'TelColumn',
                'name' => 'user_phone',
                'value' => '$data->user_phone',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'delivery_id',
                'filter' => Html::listData(ShopDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
                'value' => '$data->delivery_name',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'payment_id',
                'filter' => Html::listData(ShopPaymentMethod::model()->findAll(), 'id', 'name'),
                'value' => '$data->payment_name',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'admin_comment',
                'value' => '$data->admin_comment',
            ),
            array(
                'name' => 'user_comment',
                'value' => '$data->user_comment',
            ),
            array(
                'name' => 'status_id',
                'value' => '$data->status->name',
                'htmlOptions' => array('class' => 'text-center')
            ),
            //  array(
            //     'class' => 'ProductsPreviewColumn'
            // ),
            array(
                'name' => 'full_price',
                'header' => Yii::t('CartModule.Order', 'FULL_PRICE'),
                'value' => 'Yii::app()->currency->number_format($data->full_price)',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'date_create',
                // 'filter' => Html::listData(ShopDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
                'value' => 'CMS::date($data->date_create)',
                'htmlOptions' => array('class' => 'text-center')
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{print}{update}{delete}',
                'buttons' => array(
                    'print' => array(
                        'icon' => 'icon-print',
                        'label' => Yii::t('CartModule.default', 'PDF_ORDER'),
                        'visible' => 'Yii::app()->user->openAccess(array("Cart.Default.*", "Cart.Default.Print"))',
                        'url' => 'Yii::app()->createUrl("/admin/cart/default/print", array("id"=>$data->id))',
                    ),
                ),
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn')
            ),
        );
    }

    public function getUrl()
    {
        return Yii::app()->createUrl('/cart/default/view', array('secret_key' => $this->secret_key));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Order the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{order}}';
    }

    /**
     * Find order by user_id
     *
     * @param mixed $id
     * @return $this
     */
    public function applyUser($id = false)
    {
        if ($id) {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => "user_id=:user_id",
                'params' => array(':user_id' => $id)
            ));
        }
        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('user_name, user_email, delivery_id, payment_id', 'required', 'on' => 'siteOrder'),
            array('user_name, user_email, discount', 'length', 'max' => 100),
            array('user_phone', 'PhoneValidator'),
            array('user_email', 'email'),
            array('status_id', 'required'),
            array('user_comment, admin_comment', 'length', 'max' => 500),
            array('user_address', 'length', 'max' => 255),
            array('delivery_id', 'validateDelivery', 'on' => 'siteOrder'),
            array('payment_id', 'validatePayment', 'on' => 'siteOrder'),
            array('status_id', 'validateStatus', 'on' => 'siteOrder'),
            array('paid', 'boolean'),
            // Search
            array('id, user_id, delivery_id, payment_id, delivery_price, total_price, status_id, paid, user_name, user_email, user_address, user_phone, user_comment, ip_create, date_create, date_update', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'products' => array(self::HAS_MANY, 'OrderProduct', 'order_id'),
            'status' => array(self::BELONGS_TO, 'OrderStatus', 'status_id'),
            'product' => array(self::BELONGS_TO, 'OrderProduct', 'id'),
            'deliveryMethod' => array(self::BELONGS_TO, 'ShopDeliveryMethod', 'delivery_id'),
            'paymentMethod' => array(self::BELONGS_TO, 'ShopPaymentMethod', 'payment_id'),
        );
    }

    /**
     * @return array
     */
    public function scopes()
    {
        $alias = $this->getTableAlias(true);
        return array(
            'new' => array('condition' => $alias . '.status_id=1'),
        );
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'historical' => array(
                'class' => 'mod.cart.behaviors.HistoricalBehavior',
            ),
            'android' => array(
                'class' => 'mod.android.behaviors.NotifyBehavior',
            ),
        );
    }

    /**
     * Check if delivery method exists
     */
    public function validateDelivery()
    {
        if (ShopDeliveryMethod::model()->countByAttributes(array('id' => $this->delivery_id)) == 0)
            $this->addError('delivery_id', self::t('ERROR_DELIVERY'));
    }

    public function validatePayment()
    {
        if (ShopPaymentMethod::model()->countByAttributes(array('id' => $this->payment_id)) == 0)
            $this->addError('payment_id', self::t('ERROR_PAYMENT'));
    }

    /**
     * Check if status exists
     */
    public function validateStatus()
    {
        if ($this->status_id && OrderStatus::model()->countByAttributes(array('id' => $this->status_id)) == 0)
            $this->addError('status_id', self::t('ERROR_STATUS'));
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->secret_key = $this->createSecretKey();

            if (!Yii::app()->user->isGuest)
                $this->user_id = Yii::app()->user->id;
        }


        // Set New status
        if (!$this->status_id)
            $this->status_id = 1;


        return parent::beforeSave();
    }


    public function replace($list, $content)
    {

        $replace = array(
            '{order_id}' => $this->id,
            '{order_key}' => $this->secret_key,
            '{order_delivery_name}' => ($this->deliveryMethod) ? $this->deliveryMethod->name : null,
            '{order_payment_name}' => ($this->paymentMethod) ? $this->paymentMethod->name : null,
            '{total_price}' => Yii::app()->currency->number_format($this->total_price),
            '{user_name}' => $this->user_name,
            '{user_phone}' => $this->user_phone,
            '{user_email}' => $this->user_email,
            '{user_address}' => $this->user_address,
            '{user_agent}' => $this->user_agent,
            '{status_name}' => $this->getStatus_name(),
            '{ip}' => $this->ip_create,
            '{user_comment}' => (isset($this->user_comment)) ? $this->user_comment : '',
            '{current_currency}' => Yii::app()->currency->active->symbol,
            '{for_payment}' => Yii::app()->currency->number_format($this->total_price + $this->delivery_price),
            '{list}' => $list,
            '{link_to_order}' => Html::link(Yii::app()->controller->createAbsoluteUrl('view', array('secret_key' => $this->secret_key)), Yii::app()->controller->createAbsoluteUrl('view', array('secret_key' => $this->secret_key)))
        );
        return CMS::textReplace($content, $replace);
    }

    private function sendEmailFormDelete()
    {
        $config = Yii::app()->settings->get('cart');
        if ($config['notify_delete_order']) {
            $mailer = Yii::app()->mail;
            $mailer->From = 'noreply@' . Yii::app()->request->serverName;
            $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
            $mailer->Subject = $this->replace('', 'Ваш заказ удален');
            $mailer->Body = $this->replace('', '
<p>Здравствуйте, <strong>{user_name}</strong></p>
<p>&nbsp;</p>
<p>Уведомляем Вас, о том что заказ <strong>№{order_id}</strong> был удален.</p>

                ');
            $mailer->AddAddress($this->user_email);
            $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
            $mailer->isHtml(true);
            $mailer->Send();
            $mailer->ClearAddresses();
        }
    }

    public function sendEmailFormChangeStatus()
    {
        $config = Yii::app()->settings->get('cart');
        if ($config['notify_change_status_order']) {
            $mailer = Yii::app()->mail;
            $mailer->From = 'noreply@' . Yii::app()->request->serverName;
            $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
            $mailer->Subject = $this->replace('', 'Ваш заказ №{order_id} Изменил статус');
            $mailer->Body = $this->replace('', '
<p>Здравствуйте, <strong>{user_name}</strong></p>
<p>&nbsp;</p>
<p>Уведомляем Вас, о том что заказ <strong>№{order_id}</strong> был изменена статус на <strong>{status_name}</strong>.</p>

                ');
            $mailer->AddAddress($this->user_email);
            $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
            $mailer->isHtml(true);
            $mailer->Send();
            $mailer->ClearAddresses();
        }
    }

    public function beforeDelete()
    {
        $this->sendEmailFormDelete();
        return parent::beforeDelete();
    }

    /**
     * @return bool
     */
    public function afterDelete()
    {

        foreach ($this->products as $ordered_product)
            $ordered_product->delete();

        return parent::afterDelete();
    }

    /**
     * Create unique key to view orders
     * @param int $size
     * @return string
     */
    public function createSecretKey($size = 10)
    {

        $result = '';
        $chars = '1234567890qweasdzxcrtyfghvbnuioplkjnm';
        while (mb_strlen($result, 'utf8') < $size) {
            $result .= mb_substr($chars, rand(0, mb_strlen($chars, 'utf8')), 1);
        }

        if (Order::model()->countByAttributes(array('secret_key' => $result)) > 0)
            $this->createSecretKey($size);

        return $result;
    }

    /**
     * Update total
     */
    public function updateTotalPrice()
    {
        $this->total_price = 0;
        $products = OrderProduct::model()->findAllByAttributes(array('order_id' => $this->id));
        foreach ($products as $p) {

            $curr_rate = Yii::app()->currency->active->rate;
            $this->total_price += $p->price * $p->quantity;

        }
        $this->save(false, false, false);
    }

    /**
     * @return int
     */
    public function updateDeliveryPrice()
    {
        $result = 0;
        $deliveryMethod = ShopDeliveryMethod::model()->findByPk($this->delivery_id);

        if ($deliveryMethod) {
            if ($deliveryMethod->price > 0) {
                if ($deliveryMethod->free_from > 0 && $this->total_price > $deliveryMethod->free_from)
                    $result = 0;
                else
                    $result = $deliveryMethod->price;
            }
        }

        $this->delivery_price = $result;
        $this->save(false, false, false);
    }

    /**
     * @return mixed
     */
    public function getStatus_name()
    {
        if ($this->status)
            return $this->status->name;
    }

    public function getStatus_color()
    {
        if ($this->status)
            return $this->status->color;
    }

    /**
     * @return mixed
     */
    public function getDelivery_name()
    {
        $model = ShopDeliveryMethod::model()->findByPk($this->delivery_id);
        if ($model)
            return $model->name;
    }

    public function getPayment_name()
    {
        $model = ShopPaymentMethod::model()->findByPk($this->payment_id);
        if ($model)
            return $model->name;
    }

    /**
     * @return mixed
     */
    public function getFull_price()
    {
        if (!$this->isNewRecord) {
            $result = $this->total_price + $this->delivery_price;
            if ($this->discount) {
                $sum = $this->discount;
                if ('%' === substr($this->discount, -1, 1))
                    $sum = $result * (int)$this->discount / 100;
                $result -= $sum;
            }
            return $result;
        }
    }

    /**
     * Add product to existing order
     *
     * @param ShopProduct $product
     * @param integer $quantity
     * @param float $price
     */
    public function addProduct($product, $quantity, $price)
    {

        if (!$this->isNewRecord) {
            $ordered_product = new OrderProduct;
            $ordered_product->order_id = $this->id;
            $ordered_product->product_id = $product->id;
            $ordered_product->currency_id = $product->currency_id;
            $ordered_product->name = $product->name;
            $ordered_product->quantity = $quantity;
            $ordered_product->sku = $product->sku;
            $ordered_product->price = $price;


            $ordered_product->save();

            // Raise event
            $event = new CModelEvent($this, array(
                'product_model' => $product,
                'ordered_product' => $ordered_product,
                'quantity' => $quantity
            ));
            $this->onProductAdded($event);
        }
    }

    /**
     * Delete ordered product from order
     *
     * @param $id
     */
    public function deleteProduct($id)
    {

        $model = OrderProduct::model()->findByPk($id);

        if ($model) {
            $model->delete();

            $event = new CModelEvent($this, array(
                'ordered_product' => $model
            ));
            $this->onProductDeleted($event);
        }
    }

    /**
     * @param $event
     */
    public function onProductAdded($event)
    {
        $this->raiseEvent('onProductAdded', $event);
    }

    /**
     * @param $event
     */
    public function onProductDeleted($event)
    {
        $this->raiseEvent('onProductDeleted', $event);
    }

    /**
     * @param $event
     */
    public function onProductQuantityChanged($event)
    {
        $this->raiseEvent('onProductQuantityChanged', $event);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getOrderedProducts()
    {

        $products = new OrderProduct;
        $products->order_id = $this->id;

        return $products->search();
    }

    /**
     * @param array $data
     */
    public function setProductQuantities(array $data)
    {
        foreach ($this->products as $product) {
            if (isset($data[$product->id])) {
                if ((int)$product->quantity !== (int)$data[$product->id]) {
                    $event = new CModelEvent($this, array(
                        'ordered_product' => $product,
                        'new_quantity' => (int)$data[$product->id]
                    ));
                    $this->onProductQuantityChanged($event);
                }

                $product->quantity = (int)$data[$product->id];
                $product->save(false, false);
            }
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('delivery_id', $this->delivery_id);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->compare('delivery_price', $this->delivery_price);
        $criteria->compare('total_price', $this->total_price);
        $criteria->compare('paid', $this->paid);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('user_email', $this->user_email, true);
        $criteria->compare('user_address', $this->user_address, true);
        $criteria->compare('user_phone', $this->user_phone, true);
        $criteria->compare('user_comment', $this->user_comment, true);
        $criteria->compare('ip_create', $this->ip_create, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('date_update', $this->date_update, true);
        $criteria->compare('is_deleted', 0, true);

        $sort = new CSort;
        $sort->defaultOrder = $this->getTableAlias() . '.date_create DESC';
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
    }

    /**
     * Load history
     *
     * @return array
     */
    public function getHistory()
    {
        $cr = new CDbCriteria;
        $cr->order = 'date_create DESC';
        return OrderHistory::model()->findAllByAttributes(array('order_id' => $this->id), $cr);
    }

}
