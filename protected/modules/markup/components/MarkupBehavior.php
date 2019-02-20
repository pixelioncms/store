<?php

Yii::import('mod.markup.MarkupModule');
Yii::import('mod.markup.models.ShopMarkup');

/**
 * Product markup behavior
 *
 * @var $owner ShopProduct
 */
class MarkupBehavior extends CActiveRecordBehavior {

    /**
     * @var mixed|null|ShopMarkup
     */
    public $appliedMarkup = null;

    /**
     * @var float product price before markup applied
     */
    //  public $originalPrice;
    public $markupPrice;
    public $markupSum;

    /**
     * @var null
     */
    public static $markups = null;

    /**
     * Attach behavior to model
     * @param $owner
     */
    public function attach($owner) {
        if (!$owner->isNewRecord && Yii::app()->controller instanceof Controller) {
            if (MarkupBehavior::$markups === null) {
                MarkupBehavior::$markups = ShopMarkup::model()
                        ->published()
                        ->applyDate()
                        ->findAll();
            }

            parent::attach($owner);
        }
    }

    /**
     * After find event
     */
    public function afterFind($event) {
        if ($this->appliedMarkup !== null)
            return;

        $user = Yii::app()->user;

        // Personal product markup
        if (!empty($this->owner->markup)) {
            $markup = new ShopMarkup();
            $markup->name = Yii::t('app', 'Наценка');
            $markup->sum = $this->owner->markup;
            $this->applyMarkup($markup);
        }

        // Process markup rules
        if (!$this->hasMarkup()) {
            foreach (MarkupBehavior::$markups as $markup) {

                $apply = false;

                // Validate category
                if ($this->searchArray($markup->categories, $this->ownerCategories)) {
                    $apply = true;

                    // Validate manufacturer
                    if (!empty($markup->manufacturers))
                        $apply = in_array($this->owner->manufacturer_id, $markup->manufacturers);

                    // Apply markup by user role. markup for admin disabled.
                    if (!empty($markup->userRoles) && $user->checkAccess('Admin') !== true) {
                        $apply = false;

                        foreach ($markup->userRoles as $role) {
                            if ($user->checkAccess($role)) {
                                $apply = true;
                                break;
                            }
                        }
                    }
                }

                if ($apply === true)
                    $this->applyMarkup($markup);
            }
        }

        // Personal markup for users.
        if (!$user->isGuest && !empty($user->model->markup) && !$this->hasMarkup()) {
            $markup = new ShopMarkup();
            $markup->name = Yii::t('app', 'Персональная наценка');
            $markup->sum = $user->model->markup;
            $this->applyMarkup($markup);
        }
    }

    /**
     * Apply markup to product and decrease its price
     * @param ShopMarkup $markup
     */
    protected function applyMarkup(ShopMarkup $markup) {

        if ($this->appliedMarkup === null) {
            $sum = $markup->sum;
            if ('%' === substr($markup->sum, -1, 1))
                $sum = $this->owner->price * (int) $sum / 100;


            $this->markupPrice = $this->owner->price + $sum;
            $this->markupSum = $markup->sum;


            $this->appliedMarkup = $markup;
        }
    }

    /**
     * Search value from $a in $b
     * @param array $a
     * @param array $b
     * @return array
     */
    protected function searchArray(array $a, array $b) {
        foreach ($a as $v)
            if (in_array($v, $b))
                return true;
        return false;
    }

    /**
     * @return array
     */
    public function getOwnerCategories() {
        $id = 'markup_product_categories' . $this->owner->date_update;
        $data = Yii::app()->cache->get($id);

        if ($data === false) {
            $data = Html::listData($this->owner->categories, 'id', 'id');
            Yii::app()->cache->set($id, $data, Yii::app()->settings->get('app', 'cache_time'));
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasMarkup() {
        return !($this->appliedMarkup === null);
    }

}
