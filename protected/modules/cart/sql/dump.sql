CREATE TABLE IF NOT EXISTS `{prefix}order_product_history` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(4) unsigned NOT NULL,
  `product_id` int(4) unsigned NOT NULL,
  `date_create` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}order` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(4) unsigned DEFAULT NULL,
  `secret_key` varchar(10) DEFAULT '',
  `delivery_id` int(4) unsigned DEFAULT NULL,
  `payment_id` int(4) unsigned NOT NULL,
  `delivery_price` float(10,2) DEFAULT NULL,
  `total_price` float(10,2) DEFAULT NULL,
  `status_id` int(4) unsigned DEFAULT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL COMMENT 'delivery address',
  `user_phone` varchar(30) DEFAULT NULL,
  `user_comment` varchar(500) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_create` CHAR(45) NOT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `admin_comment` text,
  `buyOneClick` TINYINT(1) NOT NULL DEFAULT '0',
  `is_deleted` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `secret_key` (`secret_key`),
  KEY `delivery_id` (`delivery_id`),
  KEY `status_id` (`status_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}order_status` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `color` varchar(7) NOT NULL,
  `ordern` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`ordern`)
) ENGINE={engine} DEFAULT CHARSET={charset};

INSERT INTO `{prefix}order_status` (`id`, `name`, `color`, `ordern`) VALUES
(1, 'Новый', '#c8f0d4', 1),
(2, 'Выполнен', '#f0c8c8', 2),
(3, 'Отправлен', '#cccccc', 3),
(4, 'Отменен', '#f0f0ad', 4),
(5, 'Возврат', '#c8cbf0', 5);

CREATE TABLE IF NOT EXISTS `{prefix}order_history` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(4) unsigned DEFAULT NULL,
  `user_id` int(4) unsigned DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `handler` varchar(255) DEFAULT NULL,
  `data_before` text,
  `data_after` text,
  `date_create` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `date_create` (`date_create`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}order_product` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(4) unsigned NOT NULL,
  `product_id` int(4) unsigned NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `configurable_id` int(11) DEFAULT NULL,
  `name` text,
  `configurable_name` text COMMENT 'Shop name of configurable product',
  `configurable_data` text,
  `variants` text,
  `quantity` smallint(6) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `configurable_id` (`configurable_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}order_status` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `color` varchar(6) NOT NULL,
  `ordern` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ordern` (`ordern`)
) ENGINE={engine} DEFAULT CHARSET={charset};




CREATE TABLE IF NOT EXISTS `{prefix}shop_delivery_method` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `price` float(10,2) DEFAULT '0.00',
  `free_from` float(10,2) DEFAULT '0.00',
  `ordern` int(4) unsigned NOT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ordern` (`ordern`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_delivery_method_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(4) unsigned NOT NULL,
  `language_id` int(4) unsigned NOT NULL,
  `name` varchar(255) DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_delivery_payment` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` int(4) unsigned DEFAULT NULL,
  `payment_id` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_id` (`delivery_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_notifications` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(4) unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};



CREATE TABLE IF NOT EXISTS `{prefix}shop_payment_method` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `currency_id` int(4) unsigned DEFAULT NULL,
  `switch` tinyint(1) DEFAULT NULL,
  `payment_system` varchar(100) DEFAULT '',
  `ordern` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `currency_id` (`currency_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}shop_payment_method_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(4) unsigned NOT NULL,
  `language_id` int(4) unsigned NOT NULL,
  `name` varchar(255) DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Cart.Default.*', 1, 'Заказы (*)', NULL, 'N;'),
('Cart.Default.AddProduct', 0, 'Добавление товара в заказ', NULL, 'N;'),
('Cart.Default.AddProductList', 0, NULL, NULL, 'N;'),
('Cart.Default.DeleteProduct', 0, 'Удаление товара с заказа', NULL, 'N;'),
('Cart.Default.History', 0, 'История заказа', NULL, 'N;'),
('Cart.Default.Print', 0, 'Печать заказа', NULL, 'N;'),
('Cart.Default.Index', 0, 'Список заказов', NULL, 'N;'),
('Cart.Default.Update', 0, 'Редактирование заказа', NULL, 'N;'),
('Cart.Default.Create', 0, 'Добавление заказа', NULL, 'N;'),
('Cart.Delivery.*', 1, 'Доставка (*)', NULL, 'N;'),
('Cart.Delivery.Delete', 0, 'Удаление доставки', NULL, 'N;'),
('Cart.Delivery.Index', 0, 'Список доставок', NULL, 'N;'),
('Cart.Delivery.Update', 0, 'Редактирование доставки', NULL, 'N;'),
('Cart.Delivery.Create', 0, 'Добавление доставки', NULL, 'N;'),
('Cart.History.*', 1, 'История заказа (*)', NULL, 'N;'),
('Cart.History.Index', 0, 'Список историй заказов', NULL, 'N;'),
('Cart.History.New', 0, NULL, NULL, 'N;'),
('Cart.Notify.*', 1, 'Уведомление о наличие товара (*)', NULL, 'N;'),
('Cart.Notify.Delivery', 0, NULL, NULL, 'N;'),
('Cart.Notify.DeliverySend', 0, NULL, NULL, 'N;'),
('Cart.Notify.Index', 0, 'Список почт для уведомлений', NULL, 'N;'),
('Cart.Notify.Send', 0, 'Отправка уведомлений', NULL, 'N;'),
('Cart.PaymentMethod.*', 1, 'Оплата (*)', NULL, 'N;'),
('Cart.PaymentMethod.Index', 0, 'Список оптал', NULL, 'N;'),
('Cart.PaymentMethod.Update', 0, 'Редактирование оплат', NULL, 'N;'),
('Cart.PaymentMethod.Create', 0, 'Добавление оплат', NULL, 'N;'),
('Cart.Settings.*', 1, 'Настройки корзины (*)', NULL, 'N;'),
('Cart.Settings.Index', 0, 'Настройки корзины', NULL, 'N;'),
('Cart.Settings.Manual', 0, 'Мануал настройки корзины', NULL, 'N;'),
('Cart.Statistics.*', 1, 'Статистика заказов (*)', NULL, 'N;'),
('Cart.Statistics.Index', 0, 'Статистика заказов', NULL, 'N;'),
('Cart.Statuses.*', 1, 'Статусы заказов (*)', NULL, 'N;'),
('Cart.Statuses.Delete', 0, 'Удаление статусов заказов', NULL, 'N;'),
('Cart.Statuses.Index', 0, 'Список статусов', NULL, 'N;'),
('Cart.Statuses.Update', 0, 'Редактирование статусов заказа', NULL, 'N;'),
('Cart.Statuses.Create', 0, 'Добавление статусамов заказа', NULL, 'N;');
