CREATE TABLE IF NOT EXISTS `{prefix}banner` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `ordern` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ordern` (`ordern`),
  KEY `switch` (`switch`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}banner_translate` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `language_id` (`language_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};