<?php

/**
 * Manage products save_fields_on_create
 */
class ProductsController extends AdminController
{

    public function allowedActions()
    {
        return 'removeFile, addOptionToAttribute, applyCommentsFilter, applyConfigurationsFilter, applyProductsFilter, assignCategories, deleteImage, duplicateProducts, loadConfigurableOptions, renderCategoryAssignWindow, renderDuplicateProductsWindow, renderProductsPriceWindow, renderVariantTable, setProducts, updateIsActive';
    }

    public function actions()
    {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ShopProduct::model(),
            ),
            'removeFile' => array(
                'class' => 'ext.bootstrap.fileinput.actions.RemoveFileAction',
                'model' => 'ShopProduct',
                'path' => 'webroot.uploads.product',
                'attribute' => 'image'
            ),
        );
    }

    /*
      public function cover($id) {
      $photo = ShopProductImage::model()->findByPk($_POST['cover']);
      $photo->setCover();
      } */

    /**
     * Display list of products
     */
    public function actionIndex()
    {

        //$this->topButtons = Html::link(Yii::t('app', 'CREATE', 0), $this->createUrl('create'), array('title' => Yii::t('admin', 'Create', 1), 'class' => 'buttonS bGreen'));
        $this->pageName = Yii::t('ShopModule.admin', 'PRODUCTS');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/products.index.js', CClientScript::POS_END);

        if (Yii::app()->settings->get('shop', 'create_btn_action')) {
            $url = $this->createUrl('create', array('ShopProduct[type_id]' => Yii::app()->settings->get('shop', 'create_btn_action'), 'ShopProduct[use_configurations]' => 0));
        } else {
            $url = $this->createUrl('create');
        }
        if (Yii::app()->user->openAccess(array("{$this->module->id}.Products.*", "{$this->module->id}.Products.Create"))) {
            $this->topButtons = array(array(
                'label' => Yii::t('ShopModule.admin', 'CREATE_PRODUCT'),
                'url' => $url,
                'icon' => 'icon-add',
                'htmlOptions' => array('class' => 'btn btn-success')
            ));
        }
        $model = new ShopProduct('search');

        if (!empty($_GET['ShopProduct']))
            $model->attributes = $_GET['ShopProduct'];

        // Pass additional params to search method.
        $params = array(
            'category' => Yii::app()->request->getParam('category', null)
        );


        //  $model->unsetAttributes();
        $this->render('index', array(
            'model' => $model,
            'params' => $params,
        ));
    }

    public function actionGetAttributes(){
        $model = ShopProduct::model()->findByPk($_GET['id']);
        $this->renderPartial('_ajax_attributes', array('model' => $model),false,false);
        Yii::app()->end();
    }

    public function actionGetRelationProducts(){
        $model = ShopProduct::model()->findByPk($_GET['id']);
        $exclude = $_GET['exclude'];
        $this->renderPartial('_ajax_relatedProducts', array('exclude' => $exclude, 'product' => $model),false,false);
        Yii::app()->end();
    }


    public function actionGetVariants(){
        $model = ShopProduct::model()->findByPk($_GET['id']);
        $this->renderPartial('_ajax_variations', array('model' => $model),false,false);
        Yii::app()->end();
    }
    /**
     * Create/update product
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false)
    {
        $this->topButtons = false;
        $config = Yii::app()->settings->get('shop');
        if ($new === true) {
            $model = new ShopProduct();
        } else {
            $model = ShopProduct::model()->findByPk($_GET['id']);
        }
        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_PRODUCT'));

        // $oldImage = $model->image;

        if (!$model->isNewRecord) {
            $this->topButtons = array(array(
                'label' => Yii::t('ShopModule.admin', 'VIEW_PRODUCT'),
                'url' => $model->getAbsoluteUrl(),

                'htmlOptions' => array('class' => 'btn btn-primary', 'target' => '_blank'),
            ));
        }
        // Apply use_configurations, configurable_attributes, type_id
        if (isset($_GET['ShopProduct']))
            $model->attributes = $_GET['ShopProduct'];


        $title = ($model->isNewRecord) ? Yii::t('ShopModule.admin', 'CREATE_PRODUCT') :
            Yii::t('ShopModule.admin', 'UPDATE_PRODUCT');

        if ($model->type)
            $title .= ' "' . Html::encode($model->type->name) . '"';

        $this->pageName = $title;


        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'PRODUCTS') => $this->createUrl('index'),
            $this->pageName
        );


        // On create new product first display "Choose type" form first.
        if ($model->isNewRecord && isset($_GET['ShopProduct']['type_id'])) {

            if (ShopProductType::model()->countByAttributes(array('id' => $model->type_id)) === '0')
                throw new CHttpException(404, Yii::t('ShopModule.admin', 'ERR_PRODUCT_TYPE'));
        }
        //$oldImage = $model->image;
        // Set main category id to have categories drop-down selected value
        if ($model->mainCategory)
            $model->main_category_id = $model->mainCategory->id;

        // Or set selected category from type pre-set.
        if ($model->type && !Yii::app()->request->isPostRequest && $model->isNewRecord)
            $model->main_category_id = $model->type->main_category;

        // Set configurable attributes on new record
        if ($model->isNewRecord) {
            if ($model->use_configurations && isset($_GET['ShopProduct']['configurable_attributes']))
                $model->configurable_attributes = $_GET['ShopProduct']['configurable_attributes'];
        }
        Yii::setPathOfAlias('update_tabs', Yii::getPathOfAlias('mod.shop.views.admin.products.update_tabs'));

        $form = new TabForm($model->getForm(), $model);
        $form->additionalTabs['Изображение'] = array(
            'id'=>'image',
            'content' => Yii::app()->controller->widget('ext.attachment.AttachmentWidget', array(
                'model' => $model,
                'skin'=>'default_fullwidth'
            ), true),
        );
        $form->additionalTabs[Yii::t('app', 'TAB_META')] = array(
            'content' => $this->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $model, 'form' => $form), true)
        );

        $form->additionalTabs[$model::t('TAB_CAT')] = array('content' => $this->renderPartial('update_tabs._tree', array('model' => $model), true));
        //$form->additionalTabs[$model::t('TAB_ATTR')] = array('content' => $this->renderPartial('update_tabs._attributes', array('model' => $model), true));
        $form->additionalTabs[$model::t('TAB_ATTR')] = array('id'=>'ajax_attributes','ajax' => $this->createUrl('getAttributes',array('id'=>$model->id)));
        //$form->additionalTabs[$model::t('TAB_REL')] = array('content' => $this->renderPartial('update_tabs._relatedProducts', array('exclude' => $model->id, 'product' => $model), true));
        $form->additionalTabs[$model::t('TAB_REL')] = array('id'=>'ajax_relation','ajax' => $this->createUrl('getRelationProducts',array('exclude' => $model->id, 'id'=>$model->id)));
        $form->additionalTabs[$model::t('TAB_VIDEO')] = array('content' => $this->renderPartial('update_tabs._video', array('model' => $model, 'form' => $form), true));
        if (Yii::app()->hasModule('comments')) {
            //TODO Не работает форма сохранения товара
            // $form->additionalTabs[$model::t('TAB_COMMENTS')] = array('content' => $this->renderPartial('update_tabs._comments', array('model' => $model), true, false));
            // $form->additionalTabs[$model::t('TAB_COMMENTS')] = array('ajax' => array('/test'));
        }
        //$form->additionalTabs[$model::t('TAB_VARIANTS')] = array('content' => $this->renderPartial('update_tabs._variations', array('model' => $model), true));
        $form->additionalTabs[$model::t('TAB_VARIANTS')] = array('id'=>'ajax_variants','ajax' => $this->createUrl('getVariants',array('id'=>$model->id)));

        if ($model->use_configurations)
            $form->additionalTabs[Yii::t('ShopModule.admin', 'UPDATE_PRODUCT_TAB_CONF')] = array(
                'content' => $this->renderPartial('_configurations', array('product' => $model), true)
            );
        if (isset($_GET['ShopProduct']['main_category_id']))
            $model->main_category_id = $_GET['ShopProduct']['main_category_id'];


        if (Yii::app()->request->isPostRequest) {

            $post = Yii::app()->request->getPost('ShopProduct');
            $model->attributes = $post;//$_POST['ShopProduct'];

            // Handle related products
            $model->setRelatedProducts(Yii::app()->getRequest()->getPost('RelatedProductId', array()));

            /* if($model->currency_id){
              $currency = ShopCurrency::model()->findByPk($model->currency_id);
              $convertPrice = $model->price*$currency->rate/$currency->rate_old;
              $model->price=$convertPrice;
              } */
            if ($model->validate() && $this->validateAttributes($model) && $this->validatePrices($model)) {
                //$model->uploadFile('image', 'webroot.uploads.product', $oldImage);
                $model->save();

                // Process categories
                // $mainCategoryId = 1;
                if (isset($post['main_category_id'])) {
                    // $mainCategoryId = $post['main_category_id'];
                    $model->setCategories(Yii::app()->request->getPost('categories', array()), $post['main_category_id']);
                }
                // Process attributes
                $this->processAttributes($model);

                // Process variants
                $this->processVariants($model);

                // Process configurations
                $this->processConfigurations($model);

                // Process prices
                $model->processPrices(Yii::app()->request->getPost('ShopProductPrices', array()));


                $message = Yii::t('app', ($model->isNewRecord) ? 'SUCCESS_CREATE' : 'SUCCESS_UPDATE');
                if (Yii::app()->request->getPost('_editable') && Yii::app()->request->isAjaxRequest) {
                    $json = array();
                    $json['success'] = true;
                    $json['message'] = $message;
                    $json['valid'] = true;
                    $json['data'] = CMap::mergeArray($model->getAttributes(), array('main_category_id' => $model->main_category_id));

                    $this->setJson($json);
                }


                //$this->processIconsImage($model);
                // if ($model->save(false, false)) {
                // }
            } else {
                foreach ($model->getErrors() as $error) {

                    $this->setNotify($error, 'danger'); //Yii::t('ShopModule.admin', 'ERR_PRODUCT_TYPE')
                }
                //   
            }

            //$redi_array = ($this->getRedirectTabsHash()) ? array('id' => $model->id, '#' => $this->getRedirectTabsHash()) : array('id' => $model->id);
            //   $this->redirect($this->createUrl('update', $redi_array));
            // $this->redirect(array('index'));
            $this->refresh();
        }

        $this->render('update', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    public function ___actionRemoveFile($attr, $pk)
    {
        $model = ShopProduct::model()->findByPk($pk);
        $filename = $model->$attr;
        if (isset($filename)) {
            $fullPath = Yii::getPathOfAlias("webroot.uploads.product") . DS . $filename;
            if (file_exists($fullPath)) {
                $model->$attr = null;
                if ($model->save(false, false, false)) {
                    unlink($fullPath);
                    $this->redirect($this->createUrl('update', array('id' => $pk)));
                    $this->setNotify(Yii::t('app', 'FILE_SUCCESS_DELETE'));
                }
            }
        }
    }

    protected function processIconsImage(ShopProduct $model)
    {
        $pathImage = Yii::getPathOfAlias('webroot.uploads.product');
        $pathIcon = Yii::getPathOfAlias('webroot.uploads');
        $image = $model->mainImage->name;
        $id = $model->id;
        $im = imagecreatefromjpeg('uploads/product/' . $image); // . $photka

        $space = 10; //промежуток между фотографиями
        $xsx = 0;
        if ($imageInfo2 = @getimagesize($pathIcon . '/' . $image)) {
            $xi = $imageInfo2[1] - 100 - $space;
            $yi = $imageInfo2[0] - 100 / 2;
        }

        $stamps = array(
            array('stamp' => 'wtm1.png', 'use' => true),
            array('stamp' => 'wtm2.png', 'use' => true),
            array('stamp' => 'wtm3.png', 'use' => false),
            array('stamp' => 'wtm4.png', 'use' => true),
            array('stamp' => 'wtm5.png', 'use' => false),
            array('stamp' => 'wtm6.png', 'use' => false),
            array('stamp' => 'wtm7.png', 'use' => false),
            array('stamp' => 'wtm8.png', 'use' => false),
            array('stamp' => 'wtm9.png', 'use' => false),
        );
        foreach ($stamps as $stp) {
            if ($stp['use']) {

                if ($imageInfo = @getimagesize($pathIcon . '/' . $stp['stamp'])) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                }

                $stamp = imagecreatefrompng($pathIcon . '/' . $stp['stamp']);
                $xi = $xi - $space - $xsx;
                imagecopyresampled($im, $stamp, $xi, $yi, 0, 0, $width, $height, $width, $height);
                imagepng($im, 'uploads/product/stamp/' . $image);
                $xsx = $width;
            }
        }

        imagedestroy($im);
        imagedestroy($stamp);


        //$medium = imagecreatetruecolor(183, 221);
        // imagecopyresampled($medium, $im, 0, 0, 0, 0, 183, 221, 480, 580);
        // imagejpeg($medium, 'uploads/products/' . $id . '/medium/' . $photka);
        //imagedestroy($im);
        //imagedestroy($stamp);
        // imagedestroy($medium);
        // die;
    }

    /**
     * Save model attributes
     * @param ShopProduct $model
     * @return boolean
     */
    protected function processAttributes(ShopProduct $model)
    {
        $attributes = new CMap(Yii::app()->request->getPost('ShopAttribute', array()));
        if (empty($attributes))
            return false;

        $deleteModel = ShopProduct::model()->findByPk($model->id);
        $deleteModel->deleteEavAttributes(array(), true);

        // Delete empty values
        foreach ($attributes as $key => $val) {
            if (is_string($val) && $val === '')
                $attributes->remove($key);
        }

        return $model->setEavAttributes($attributes->toArray(), true);
    }

    /**
     * Save product variants
     * @param ShopProduct $model
     */
    protected function processVariants(ShopProduct $model)
    {
        $dontDelete = array();

        if (!empty($_POST['variants'])) {
            foreach ($_POST['variants'] as $attribute_id => $values) {
                $i = 0;
                foreach ($values['option_id'] as $option_id) {
                    // Try to load variant from DB
                    $variant = ShopProductVariant::model()->findByAttributes(array(
                        'product_id' => $model->id,
                        'attribute_id' => $attribute_id,
                        'option_id' => $option_id
                    ));
                    // If not - create new.
                    if (!$variant)
                        $variant = new ShopProductVariant();

                    $variant->setAttributes(array(
                        'attribute_id' => $attribute_id,
                        'option_id' => $option_id,
                        'product_id' => $model->id,
                        'price' => $values['price'][$i],
                        'price_type' => $values['price_type'][$i],
                        'sku' => $values['sku'][$i],
                    ), false);

                    $variant->save(false, false, false);
                    array_push($dontDelete, $variant->id);
                    $i++;
                }
            }
        }

        if (!empty($dontDelete)) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('id', $dontDelete);
            $cr->addCondition('product_id=' . $model->id);
            ShopProductVariant::model()->deleteAll($cr);
        } else
            ShopProductVariant::model()->deleteAllByAttributes(array('product_id' => $model->id));
    }

    /**
     * Save product configurations
     * @param ShopProduct $model
     * @return mixed
     */
    protected function processConfigurations(ShopProduct $model)
    {
        $productPks = Yii::app()->request->getPost('ConfigurationsProductGrid_c0', array());

        // Clear relations
        Yii::app()->db->createCommand()->delete('{{shop_product_configurations}}', 'product_id=:id', array(':id' => $model->id));

        if (!sizeof($productPks))
            return;

        foreach ($productPks as $pk) {
            Yii::app()->db->createCommand()->insert('{{shop_product_configurations}}', array(
                'product_id' => $model->id,
                'configurable_id' => $pk
            ));
        }
    }

    /**
     * Create gridview for "Related Products" tab
     * @param int $exclude Product id to exclude from list
     */
    public function actionApplyProductsFilter($exclude = 0)
    {
        $model = new ShopProduct('search');
        $model->exclude = $exclude;

        if (!empty($_GET['RelatedProducts']))
            $model->attributes = $_GET['RelatedProducts'];

        $this->renderPartial('_relatedProducts', array(
            'model' => $model,
            'exclude' => $exclude,
        ));
    }

    /**
     * Render configurations tab gridview.
     */
    public function actionApplyConfigurationsFilter()
    {
        $product = ShopProduct::model()->findByPk($_GET['product_id']);

        // On create new product
        if (!$product) {
            $product = new ShopProduct();
            $product->configurable_attributes = $_GET['configurable_attributes'];
        }

        $this->renderPartial('_configurations', array(
            'product' => $product,
            'clearConfigurations' => true // Show all products
        ));
    }

    /**
     * Render comments tab
     */
    public function actionApplyCommentsFilter()
    {
        $this->renderPartial('_comments', array(
            'model' => ShopProduct::model()->findByPk($_GET['id'])
        ));
    }

    /**
     * @throws CHttpException
     */
    public function actionRenderVariantTable()
    {
        $attribute = ShopAttribute::model()
            ->findByPk($_GET['attr_id']);

        if (!$attribute)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'ERR_LOAD_ATTR'));

        $this->renderPartial('variants/_table', array(
            'attribute' => $attribute
        ));
    }

    /**
     * Load attributes relative to type and available for product configurations.
     * Used on creating new product.
     */
    public function actionLoadConfigurableOptions()
    {
        // For configurations that  are available only dropdown and radio lists.
        $cr = new CDbCriteria;
        $cr->addInCondition('type', array(ShopAttribute::TYPE_DROPDOWN, ShopAttribute::TYPE_RADIO_LIST));
        $type = ShopProductType::model()->with(array('shopAttributes'))->findByPk($_GET['type_id'], $cr);

        $data = array();
        if ($type->shopAttributes) {
            $data = array('status' => 'success');
            foreach ($type->shopAttributes as $attr) {
                $data['response'][] = array(
                    'id' => $attr->id,
                    'title' => $attr->title,
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'Ошибка не найден не один атрибут'
            );
        }

        echo json_encode($data);
    }

    /**
     * @param $id ShopProductImage id
     */
    public function actionDeleteImage($id)
    {
        if (Yii::app()->request->getIsPostRequest()) {
            $model = ShopProductImage::model()->findByPk($id);
            if ($model)
                $model->delete();
        }
    }

    /**
     * Mass product update switch
     */
    public function actionUpdateIsActive()
    {
        $ids = Yii::app()->request->getPost('ids');
        $switch = (int)Yii::app()->request->getPost('switch');
        $models = ShopProduct::model()->findAllByPk($ids);
        foreach ($models as $product) {
            if (in_array($switch, array(0, 1))) {
                $product->switch = $switch;
                $product->save(false, false, false);
            }
        }
        echo Yii::t('app', 'SUCCESS_UPDATE');
        Yii::app()->end();
    }

    /**
     * Delete products
     * @param array $id
     */
    public function actionDelete2222($id = array())
    {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopProduct::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $page)
                    $page->delete();
            }

            if (!Yii::app()->request->isAjaxRequest) {
                $this->redirect('index');
            }
        }
    }

    /**
     * Validate required shop attributes
     * @param ShopProduct $model
     * @return bool
     */
    public function validateAttributes(ShopProduct $model)
    {
        $attributes = $model->type->shopAttributes;

        if (empty($attributes) || $model->use_configurations) {
            return true;
        }

        $errors = false;
        foreach ($attributes as $attr) {
            if ($attr->required && empty($_POST['ShopAttribute'][$attr->name])) {
                $errors = true;
                $model->addError($attr->name, Yii::t('ShopModule.admin', 'FIEND_REQUIRED', array('{field}' => $attr->title)));
            }
        }

        return !$errors;
    }

    public function validatePrices(ShopProduct $model)
    {
        $pricesPost = Yii::app()->request->getPost('ShopProductPrices', array());

        $errors = false;
        $orderFrom = array();

        foreach ($pricesPost as $index => $price) {
            $orderFrom[] = $price['order_from'];
            if ($price['value'] >= $model->price) {
                $errors = true;
                $model->addError('price', $model::t('ERROR_PRICE_MAX_BASIC'));
            }
        }

        if (count($orderFrom) !== count(array_unique($orderFrom))) {
            $errors = true;
            $model->addError('price', $model::t('ERROR_PRICE_DUPLICATE_ORDER_FROM'));
        }

        return !$errors;
    }

    /**
     * Add option to shop attribute
     *
     * @throws CHttpException
     */
    public function actionAddOptionToAttribute()
    {
        $attribute = ShopAttribute::model()
            ->findByPk($_GET['attr_id']);

        if (!$attribute)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'ERR_LOAD_ATTR'));

        $attributeOption = new ShopAttributeOption;
        $attributeOption->attribute_id = $attribute->id;
        $attributeOption->value = $_GET['value'];
        $attributeOption->save(false, false, false);

        echo $attributeOption->id;
    }

    /**
     * Updates image titles
     */
    public function updateImageTitles()
    {
        if (sizeof(Yii::app()->request->getPost('image_titles', array()))) {
            foreach (Yii::app()->request->getPost('image_titles', array()) as $id => $title) {
                ShopProductImage::model()->updateByPk($id, array(
                    'title' => $title
                ));
            }
        }
    }

    /**
     * Render popup window
     */
    public function actionRenderCategoryAssignWindow()
    {
        Yii::app()->getClientScript()->scriptMap = array(
            'jquery.js' => false,
            'jquery.min.js' => false,
        );
        $this->renderPartial('category_assign_window', array(), false, true);
    }

    /**
     * Render popup windows
     */
    public function actionRenderDuplicateProductsWindow()
    {
        $this->renderPartial('duplicate_products_window');
    }

    /**
     * Render popup windows
     */
    public function actionRenderProductsPriceWindow()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $model = new ShopProduct();
            $this->renderPartial('products_price_window', array('model' => $model));
        } else {
            throw new CException(Yii::t('http_error', '403'), 403);
        }
    }

    /**
     * Set price products
     */
    public function actionSetProducts()
    {
        $request = Yii::app()->request;
        if ($request->isAjaxRequest) {
            $product_ids = $request->getPost('products', array());
            parse_str($request->getPost('data'), $price);
            $products = ShopProduct::model()->findAllByPk($product_ids);
            foreach ($products as $p) {
                if (isset($p)) {
                    if (!$p->currency_id || !$p->use_configurations) { //запрещаем редактирование товаров с привязанной ценой и/или концигурациями
                        $p->price = $price['ShopProduct']['price'];
                        if ($p->validate()) {
                            $p->save(false, false);
                        }
                    }
                }
            }
        } else {
            throw new CException(Yii::t('http_error', '403'), 403);
        }
    }

    /**
     * Duplicate products
     */
    public function actionDuplicateProducts()
    {
        //TODO: return ids to find products
        $product_ids = Yii::app()->request->getPost('products', array());
        parse_str(Yii::app()->request->getPost('duplicate'), $duplicates);

        if (!isset($duplicates['copy']))
            $duplicates['copy'] = array();

        $duplicator = new SProductsDuplicator;
        $ids = $duplicator->createCopy($product_ids, $duplicates['copy']);
        echo '/admin/shop/products/?ShopProduct[id]=' . implode(',', $ids);
    }

    /**
     * Assign categories to products
     */
    public function actionAssignCategories()
    {
        $categories = Yii::app()->request->getPost('category_ids');
        $products = Yii::app()->request->getPost('product_ids');

        if (empty($categories) || empty($products))
            return;

        $products = ShopProduct::model()->findAllByPk($products);

        foreach ($products as $p)
            $p->setCategories($categories, Yii::app()->request->getPost('main_category'));
    }


}
