CREATE TABLE IF NOT EXISTS `{prefix}android_fcm_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE={engine} DEFAULT CHARSET={charset};