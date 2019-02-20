CREATE TABLE IF NOT EXISTS `{prefix}shop_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `sum` varchar(10) DEFAULT '',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `switch` (`switch`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `category_id` (`category_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `manufacturer_id` (`manufacturer_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Discounts.Default.*', 1, 'Скидки (*)', NULL, 'N;'),
('Discounts.Default.Index', 0, 'Список скидок', NULL, 'N;'),
('Discounts.Default.Delete', 0, 'Удаление скидок', NULL, 'N;'),
('Discounts.Default.Switch', 0, 'Скрыть/показать скидку', NULL, 'N;'),
('Discounts.Default.Update', 0, 'Редактирование скидок', NULL, 'N;'),
('Discounts.Default.Create', 0, 'Добавление скидок', NULL, 'N;');