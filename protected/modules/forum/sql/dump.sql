CREATE TABLE IF NOT EXISTS `{prefix}forum_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `lft` int(10) UNSIGNED DEFAULT NULL,
  `rgt` int(10) UNSIGNED DEFAULT NULL,
  `level` smallint(5) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `hint` text,
  `seo_alias` varchar(255) DEFAULT NULL,
  `full_path` varchar(255) DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `last_topic_id` int(11) DEFAULT NULL,
  `last_post_id` int(11) DEFAULT NULL,
  `last_post_user_id` int(11) DEFAULT NULL,
  `count_topics` int(11) NOT NULL DEFAULT '0',
  `count_posts` int(11) NOT NULL DEFAULT '0',
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `url` (`seo_alias`),
  KEY `full_path` (`full_path`)
) ENGINE={engine} DEFAULT CHARSET={charset};

INSERT INTO `{prefix}forum_categories` (`id`, `lft`, `rgt`, `level`, `name`, `hint`, `seo_alias`, `full_path`, `image`, `date_create`, `date_update`, `last_topic_id`, `last_post_id`, `last_post_user_id`, `count_topics`, `count_posts`, `switch`) VALUES
(1, 1, 12, 1, 'Тест кат', 'хъахахахах', 'root', '', NULL, NULL, '2017-03-17 23:11:53', 19, 37, 1, 0, 0, 1),
(2, 2, 5, 2, 'Штакеты', 'ваыффываавфы', NULL, '', NULL, '2017-03-20 06:58:09', '2017-03-17 22:59:08', 13, 27, 1, 0, 0, 1),
(3, 6, 7, 2, 'sdfa', 'fadsafds', NULL, '', NULL, '2017-03-15 22:58:38', '2017-03-16 09:11:08', 8, 47, 4, 0, 0, 1),
(4, 8, 9, 2, 'gagagag', '111', NULL, '', NULL, '2017-03-16 13:50:56', '2017-03-17 22:53:45', 14, 28, 1, 0, 0, 1),
(5, 10, 11, 2, 'asfdfdasafds', '', NULL, '', NULL, '2017-03-16 13:52:18', '2017-03-17 23:11:53', 19, 37, 1, 0, 0, 1),
(6, 3, 4, 3, 'hahah', '', NULL, '', NULL, '2017-03-17 11:53:36', '2017-03-17 22:44:07', 7, 22, 1, 0, 0, 1);



CREATE TABLE `{prefix}forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `text` text,
  `score` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `edit_user_id` int(11) DEFAULT NULL,
  `edit_reason` text,
  `edit_datetime` datetime DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `ip_create` varchar(255) DEFAULT NULL,
  `user_agent` text,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};



CREATE TABLE `{prefix}forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `is_close` tinyint(1) NOT NULL DEFAULT '0',
  `fixed` tinyint(1) NOT NULL DEFAULT '0',
  `last_post_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};