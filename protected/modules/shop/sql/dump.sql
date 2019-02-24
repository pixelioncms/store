CREATE TABLE IF NOT EXISTS `{prefix}shop_attribute` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(4) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `type` tinyint(4) DEFAULT NULL,
  `display_on_front` tinyint(1) DEFAULT '1',
  `use_in_filter` tinyint(1) DEFAULT NULL,
  `use_in_variants` tinyint(1) DEFAULT NULL,
  `use_in_compare` tinyint(1) DEFAULT '0',
  `select_many` tinyint(1) DEFAULT NULL,
  `ordern` int(11) DEFAULT NULL,
  `required` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `use_in_filter` (`use_in_filter`),
  KEY `display_on_front` (`display_on_front`),
  KEY `use_in_variants` (`use_in_variants`),
  KEY `use_in_compare` (`use_in_compare`),
  KEY `name` (`name`),
  KEY `ordern` (`ordern`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_attribute_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(4) unsigned NOT NULL,
  `language_id` int(4) unsigned NOT NULL,
  `title` varchar(255) DEFAULT '',
  `abbreviation` varchar(255) DEFAULT '',
  `hint` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_attribute_option` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(4) unsigned NOT NULL,
  `attribute_id` int(4) unsigned NOT NULL,
  `spec` text DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `ordern` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `user_id` (`user_id`),
  KEY `ordern` (`ordern`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_attribute_option_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(4) unsigned NOT NULL,
  `object_id` int(4) unsigned NOT NULL,
  `value` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `object_id` (`object_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};



CREATE TABLE IF NOT EXISTS `{prefix}shop_attribute_groups` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `ordern` int(4) unsigned DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ordern` (`ordern`),
  KEY `switch` (`switch`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_attribute_groups_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(4) unsigned NOT NULL,
  `object_id` int(4) unsigned NOT NULL,
  `name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `object_id` (`object_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_category` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(4) unsigned DEFAULT NULL,
  `rgt` int(4) unsigned DEFAULT NULL,
  `level` smallint(5) unsigned DEFAULT NULL,
  `image` varchar(15) DEFAULT NULL,
  `seo_alias` varchar(255) DEFAULT NULL,
  `full_path` varchar(255) DEFAULT '',
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `url` (`seo_alias`),
  KEY `full_path` (`full_path`),
  KEY `switch` (`switch`)
) ENGINE={engine} DEFAULT CHARSET={charset};

INSERT INTO `{prefix}shop_category` (`id`, `lft`, `rgt`, `level`, `seo_alias`, `full_path`, `switch`) VALUES
(1, 1, 2, 1, 'root', '', 1);

CREATE TABLE IF NOT EXISTS `{prefix}shop_category_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(4) unsigned NOT NULL,
  `language_id` int(4) unsigned NOT NULL,
  `name` varchar(255) DEFAULT '',
  `description` text,
  `seo_product_title` varchar(255) DEFAULT NULL,
  `seo_product_keywords` varchar(255) DEFAULT NULL,
  `seo_product_description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`),
  KEY `name` (`name`)
) ENGINE={engine} DEFAULT CHARSET={charset};

INSERT INTO `{prefix}shop_category_translate` (`id`, `object_id`, `language_id`, `name`, `description`, `seo_product_title`, `seo_product_keywords`, `seo_product_description`) VALUES
(1, 1, 1, 'Каталог продукции', NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `{prefix}shop_currency` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `iso` varchar(10) DEFAULT '',
  `symbol` varchar(10) DEFAULT '',
  `rate` float(10,3) DEFAULT NULL,
  `penny` varchar(5) DEFAULT NULL,
  `separator_hundredth` varchar(5) DEFAULT NULL,
  `separator_thousandth` varchar(5) DEFAULT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};




CREATE TABLE IF NOT EXISTS `{prefix}shop_manufacturer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned DEFAULT NULL,
  `currency_id` smallint(6) unsigned DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `seo_alias` varchar(255) DEFAULT '',
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `ordern` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `seo_alias` (`seo_alias`),
  KEY `cat_id` (`cat_id`),
  KEY `currency_id` (`currency_id`),
  KEY `ordern` (`ordern`),
  KEY `switch` (`switch`)
) ENGINE={engine} DEFAULT CHARSET={charset};



CREATE TABLE IF NOT EXISTS `{prefix}shop_manufacturer_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_product` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(4) unsigned DEFAULT NULL,
  `manufacturer_id` int(4) unsigned DEFAULT NULL,
  `type_id` int(4) unsigned DEFAULT NULL,
  `supplier_id` int(4) unsigned DEFAULT NULL,
  `currency_id` smallint(6) DEFAULT NULL,
  `use_configurations` tinyint(1) NOT NULL DEFAULT '0',
  `seo_alias` varchar(255) NOT NULL,
  `unit` TINYINT(1) UNSIGNED NOT NULL
  `price` float(10,2) DEFAULT NULL,
  `max_price` float(10,2) NOT NULL DEFAULT '0.00',
  `price_purchase` float(10,2) DEFAULT NULL COMMENT 'Цена без накрутки',
  `label` VARCHAR (10) DEFAULT NULL COMMENT 'hot, popular etc',
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `sku` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `archive` tinyint(1) DEFAULT '0',
  `availability` tinyint(2) DEFAULT '1',
  `auto_decrease_quantity` tinyint(2) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `added_to_cart_count` int(11) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `discount` CHAR(50) DEFAULT NULL,
  `markup` CHAR(50) DEFAULT NULL,
  `video` text,
  `ordern` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manufacturer_id` (`manufacturer_id`),
  KEY `type_id` (`type_id`),
  KEY `price` (`price`),
  KEY `max_price` (`max_price`),
  KEY `switch` (`switch`),
  KEY `sku` (`sku`),
  KEY `date_create` (`date_create`),
  KEY `date_update` (`date_update`),
  KEY `added_to_cart_count` (`added_to_cart_count`),
  KEY `views` (`views`),
  KEY `ordern` (`ordern`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_product_prices` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `value` float(10,2) DEFAULT NULL,
  `order_from` smallint(6) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_product_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(4) unsigned NOT NULL,
  `language_id` int(4) unsigned NOT NULL,
  `name` varchar(255) DEFAULT '',
  `short_description` text,
  `full_description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_product_attribute_eav` (
  `entity` int(4) unsigned NOT NULL,
  `attribute` varchar(250) DEFAULT '',
  `value` text,
  KEY `entity` (`entity`),
  KEY `attribute` (`attribute`),
  KEY `value` (`value`(50))
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_product_category_ref` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `product` int(4) unsigned NOT NULL,
  `category` int(4) unsigned NOT NULL,
  `is_main` tinyint(1) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `category` (`category`),
  KEY `is_main` (`is_main`),
  KEY `switch` (`switch`)
) ENGINE={engine} DEFAULT CHARSET={charset};



CREATE TABLE IF NOT EXISTS `{prefix}shop_product_configurable_attributes` (
  `product_id` int(4) unsigned NOT NULL COMMENT 'Attributes available to configure product',
  `attribute_id` int(4) unsigned NOT NULL,
  UNIQUE KEY `product_attribute_index` (`product_id`,`attribute_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_product_configurations` (
  `product_id` int(4) unsigned NOT NULL COMMENT 'Saves relations beetwen product and configurations',
  `configurable_id` int(4) unsigned NOT NULL,
  UNIQUE KEY `idsunique` (`product_id`,`configurable_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_product_type` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `categories_preset` text,
  `attribute_group` tinyint(1) NOT NULL DEFAULT '1',
  `main_category` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_product_variant` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` int(4) unsigned NOT NULL,
  `option_id` int(4) unsigned NOT NULL,
  `product_id` int(4) unsigned NOT NULL,
  `price` float(10,2) DEFAULT '0.00',
  `price_type` tinyint(1) DEFAULT NULL,
  `sku` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `option_id` (`option_id`),
  KEY `product_id` (`product_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_related_product` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(4) unsigned NOT NULL,
  `related_id` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_suppliers` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_type_attribute` (
  `type_id` int(4) unsigned NOT NULL,
  `attribute_id` int(4) unsigned NOT NULL,
  PRIMARY KEY (`type_id`,`attribute_id`),
  KEY `attribute_id` (`attribute_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};



INSERT INTO `{prefix}shop_currency` (`id`, `name`, `iso`, `symbol`, `rate`, `penny`, `separator_hundredth`, `separator_thousandth`, `is_main`, `is_default`) VALUES
(1, 'Гривна', 'UAH', 'грн.', 1.000, '0', ' ', ' ', '1', '1');


INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Shop.Attribute.*', 1, 'Атрибуты (*)', NULL, 'N;'),
('Shop.Attribute.Delete', 0, 'Удаление атрибута', NULL, 'N;'),
('Shop.Attribute.Index', 0, 'Список атрибутов', NULL, 'N;'),
('Shop.Attribute.Update', 0, 'Редактирование атрибута', NULL, 'N;'),
('Shop.Attribute.Create', 0, 'Добавление атрибута', NULL, 'N;'),
('Shop.Attribute.Sortable', 0, 'Сортировать атрибуты', NULL, 'N;'),
('Shop.AttributeGroups.*', 1, 'Группы атрибутов (*)', NULL, 'N;'),
('Shop.AttributeGroups.Index', 0, 'Группы атрибутов', NULL, 'N;'),
('Shop.AttributeGroups.Update', 0, 'Редактирование групп атрибутов', NULL, 'N;'),
('Shop.AttributeGroups.Create', 0, 'Добавление групп атрибутов', NULL, 'N;'),
('Shop.Category.*', 1, 'Категории (*)', NULL, 'N;'),
('Shop.Category.Create', 0, 'Добавление категории', NULL, 'N;'),
('Shop.Category.CreateNode', 0, NULL, NULL, 'N;'),
('Shop.Category.CreateRoot', 0, NULL, NULL, 'N;'),
('Shop.Category.Delete', 0, 'Удаление категории', NULL, 'N;'),
('Shop.Category.Index', 0, 'Список категорий', NULL, 'N;'),
('Shop.Category.MoveNode', 0, 'Сортировка категорий (dnd)', NULL, 'N;'),
('Shop.Category.RenameNode', 0, 'Переименовать категорию', NULL, 'N;'),
('Shop.Category.SwitchNode', 0, 'Скрыть/показать категорию', NULL, 'N;'),
('Shop.Category.Update', 0, 'Управление категорией', NULL, 'N;'),
('Shop.Currency.*', 1, 'Валюта (*)', NULL, 'N;'),
('Shop.Currency.Delete', 0, 'Удаление валюты', NULL, 'N;'),
('Shop.Currency.Index', 0, 'Список валют', NULL, 'N;'),
('Shop.Currency.Update', 0, 'Редактирование валюты', NULL, 'N;'),
('Shop.Currency.Create', 0, 'Добавление валюты', NULL, 'N;'),
('Shop.Currency.UpdateOld', 0, NULL, NULL, 'N;'),
('Shop.Currency.UpdateProductPrice', 0, NULL, NULL, 'N;'),
('Shop.Currency.UpdateProducts', 0, NULL, NULL, 'N;'),
('Shop.Default.*', 1, 'Магазин (*)', NULL, 'N;'),
('Shop.Default.AjaxRoot', 0, NULL, NULL, 'N;'),
('Shop.Default.Index', 0, 'Магазин', NULL, 'N;'),
('Shop.Default.RefreshViews', 0, NULL, NULL, 'N;'),
('Shop.Manufacturer.*', 1, 'Производители (*)', NULL, 'N;'),
('Shop.Manufacturer.Index', 0, 'Список производителей', NULL, 'N;'),
('Shop.Manufacturer.Update', 0, 'Редактирование производителя', NULL, 'N;'),
('Shop.Manufacturer.Create', 0, 'Добавление производителя', NULL, 'N;'),
('Shop.Manufacturer.Sortable', 0, 'Сортировать производителей', NULL, 'N;'),
('Shop.Manufacturer.Switch', 0, 'Скрыть/показать производителя', NULL, 'N;'),
('Shop.Products.*', 1, 'Продукция (*)', NULL, 'N;'),
('Shop.Products.Index', 0, 'Список товаров', NULL, 'N;'),
('Shop.Products.Delete', 0, 'Удаление товара', NULL, 'N;'),
('Shop.Products.Switch', 0, 'Скрыть/показать товар', NULL, 'N;'),
('Shop.Products.Sortable', 0, 'Сортировать товары', NULL, 'N;'),
('Shop.Products.Update', 0, 'Редактирование товаром', NULL, 'N;'),
('Shop.Products.Create', 0, 'Добавление товара', NULL, 'N;'),
('Shop.ProductType.*', 1, 'Тип товаров (*)', NULL, 'N;'),
('Shop.ProductType.Delete', 0, 'Удаление типов товара', NULL, 'N;'),
('Shop.ProductType.Index', 0, 'Cписок типа товаров', NULL, 'N;'),
('Shop.ProductType.Update', 0, 'Редактирование типов товара', NULL, 'N;'),
('Shop.ProductType.Create', 0, 'Добавление типов товара', NULL, 'N;'),
('Shop.Settings.*', 1, 'Настройки магазина (*)', NULL, 'N;'),
('Shop.Settings.Index', 0, 'Настройки магазина', NULL, 'N;'),
('Shop.Suppliers.*', 1, 'Поставщики (*)', NULL, 'N;'),
('Shop.Suppliers.Index', 0, 'Список поставщиков', NULL, 'N;'),
('Shop.Suppliers.Update', 0, 'Редактирование поставщика', NULL, 'N;'),
('Shop.Suppliers.Create', 0, 'Добавление поставщика', NULL, 'N;');
