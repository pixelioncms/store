CREATE TABLE IF NOT EXISTS `{prefix}delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Delivery.Default.*', 1, 'Рассылка (*)', NULL, 'N;'),
('Delivery.Default.Index', 0, 'Список рассылок', NULL, 'N;'),
('Delivery.Default.Delete', 0, 'Удаление рассылок', NULL, 'N;'),
('Delivery.Default.Switch', 0, 'Скрыть/показать рассылку', NULL, 'N;'),
('Delivery.Default.Update', 0, 'Редактирование рассылок', NULL, 'N;'),
('Delivery.Default.Create', 0, 'Добавление рассылки', NULL, 'N;'),
('Delivery.Default.CreateDelivery', 0, 'Delivery.Default.CreateDelivery', NULL, 'N;'),
('Delivery.Default.Sendmail', 0, 'Delivery.Default.Sendmail', NULL, 'N;'),
('Delivery.Default.SendNewProduct', 0, 'Delivery.Default.SendNewProduct', NULL, 'N;');
