CREATE TABLE IF NOT EXISTS `{prefix}exchange1c` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(4) unsigned NOT NULL,
  `object_type` int(11) DEFAULT NULL,
  `external_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `object_type` (`object_type`),
  KEY `external_id` (`external_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};
