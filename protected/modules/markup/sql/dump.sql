CREATE TABLE IF NOT EXISTS `{prefix}shop_markup` (
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


CREATE TABLE IF NOT EXISTS `{prefix}shop_markup_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `markup_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `markup_id` (`markup_id`),
  KEY `category_id` (`category_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}shop_markup_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `markup_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `markup_id` (`markup_id`),
  KEY `manufacturer_id` (`manufacturer_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Markup.Default.*', 1, 'Наценки (*)', NULL, 'N;'),
('Markup.Default.Index', 0, 'Список наценок', NULL, 'N;'),
('Markup.Default.Delete', 0, 'Удаление наценок', NULL, 'N;'),
('Markup.Default.Switch', 0, 'Скрыть/показать наценку', NULL, 'N;'),
('Markup.Default.Update', 0, 'Редактирование наценок', NULL, 'N;'),
('Markup.Default.Create', 0, 'Добавление наценок', NULL, 'N;');