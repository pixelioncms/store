CREATE TABLE IF NOT EXISTS `{prefix}news` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `seo_alias` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `ordern` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `seo_alias` (`seo_alias`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}news_translate` (
  `id` BIGINT(8) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` BIGINT(8) unsigned DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `title` varchar(140) DEFAULT NULL,
  `short_text` text,
  `full_text` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('News.Default.*', 1, 'Новости (*)', NULL, 'N;'),
('News.Default.Index', 0, 'Список новостей', NULL, 'N;'),
('News.Default.Delete', 0, 'Удаление новостей', NULL, 'N;'),
('News.Default.Update', 0, 'Редактирование новостей', NULL, 'N;'),
('News.Default.Create', 0, 'Добавление новостей', NULL, 'N;'),
('News.Default.Switch', 0, 'Скрыть/показать новость', NULL, 'N;'),
('News.Default.Sortable', 0, 'Сортировка новостей', NULL, 'N;'),
('News.Default.DeleteFile', 0, 'Удаление файла новостей', NULL, 'N;'),
('News.Settings.*', 1, 'Настройки новостей (*)', NULL, 'N;'),
('News.Settings.Index', 0, 'Настройки новостей', NULL, 'N;');