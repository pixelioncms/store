CREATE TABLE IF NOT EXISTS `{prefix}wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(10) DEFAULT '',
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`),
  KEY `user_id` (`user_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}wishlist_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wishlist_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlist_id` (`wishlist_id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE={engine} DEFAULT CHARSET={charset};