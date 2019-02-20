CREATE TABLE IF NOT EXISTS `{prefix}page` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `seo_alias` varchar(255) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `in_menu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `seo_alias` (`seo_alias`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}page_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` BIGINT(8) unsigned DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `full_text` text,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `object_id` (`object_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Pages.Default.*', 1, 'Статичные страницы (*)', NULL, 'N;'),
('Pages.Default.Index', 0, 'Список страниц', NULL, 'N;'),
('Pages.Default.Delete', 0, 'Удаление страниц', NULL, 'N;'),
('Pages.Default.Switch', 0, 'Скрыть/показать страницу', NULL, 'N;'),
('Pages.Default.Update', 0, 'Редактирование страниц', NULL, 'N;'),
('Pages.Default.Create', 0, 'Добавление страниц', NULL, 'N;');