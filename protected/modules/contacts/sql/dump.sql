CREATE TABLE IF NOT EXISTS `{prefix}contacts_cites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}contacts_cites_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}contacts_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `zoom` tinyint(2) DEFAULT '14',
  `height` varchar(5) DEFAULT NULL,
  `width` varchar(5) DEFAULT '100%',
  `center` point NOT NULL,
  `type` enum('roadmap','satellite','hybrid','terrain') DEFAULT 'roadmap',
  `drag` tinyint(1) DEFAULT '1',
  `grayscale` tinyint(1) DEFAULT '0',
  `night_mode` tinyint(1) DEFAULT '1',
  `scrollwheel` tinyint(1) DEFAULT '1',
  `transitLayer` tinyint(1) DEFAULT '0',
  `trafficLayer` tinyint(1) DEFAULT '0',
  `fullscreenControl` tinyint(2) NOT NULL DEFAULT '3',
  `streetViewControl` tinyint(2) NOT NULL DEFAULT '3',
  `mapTypeControl` tinyint(2) DEFAULT '2',
  `zoomControl` tinyint(2) DEFAULT '5',
  `scaleControl` tinyint(1) DEFAULT '1',
  `rotateControl` tinyint(1) DEFAULT '1',
  `auto_show_routers` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};



CREATE TABLE IF NOT EXISTS `{prefix}contacts_markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) DEFAULT NULL,
  `coords` point NOT NULL,
  `name` text,
  `icon_file` varchar(255) DEFAULT NULL,
  `icon_file_offset_x` varchar(5) DEFAULT NULL,
  `icon_file_offset_y` varchar(5) DEFAULT NULL,
  `icon_content` varchar(255) DEFAULT NULL,
  `hint_content` varchar(255) DEFAULT NULL,
  `balloon_content_body` varchar(255) DEFAULT NULL,
  `balloon_content_footer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}contacts_router` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) DEFAULT NULL,
  `start_coords` point NOT NULL,
  `end_coords` point NOT NULL,
  `mapStateAutoApply` tinyint(1) NOT NULL DEFAULT '0',
  `name` text,
  `opacity` float(2,1) DEFAULT NULL,
  `preset` varchar(50) DEFAULT 'islands#icon',
  `color` varchar(7) DEFAULT '#0095b6',
  `start_icon_content` varchar(255) DEFAULT 'A',
  `end_icon_content` varchar(255) DEFAULT 'B',
  `start_balloon_content_body` text,
  `end_balloon_content_body` text,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};


CREATE TABLE IF NOT EXISTS `{prefix}contacts_router_translate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE={engine} DEFAULT CHARSET={charset};



INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Contacts.Default.*', 1, 'Контакты (*)', NULL, 'N;'),
('Contacts.Default.Index', 0, 'Контакты', NULL, 'N;'),
('Contacts.Maps.*', 1, 'Карты (*)', NULL, 'N;'),
('Contacts.Maps.Index', 0, 'Список карт', NULL, 'N;'),
('Contacts.Maps.Create', 0, 'Добавление карты', NULL, 'N;'),
('Contacts.Maps.Update', 0, 'Редактирование карты', NULL, 'N;'),
('Contacts.Markers.*', 1, 'Маркеры (*)', NULL, 'N;'),
('Contacts.Markers.Index', 0, 'Список маркеров', NULL, 'N;'),
('Contacts.Markers.Create', 0, 'Добавление каркеров', NULL, 'N;'),
('Contacts.Markers.Update', 0, 'Редактирование каркеров', NULL, 'N;'),
('Contacts.Router.*', 1, 'Маршруты (*)', NULL, 'N;'),
('Contacts.Router.Index', 0, 'Список Маршрутов', NULL, 'N;'),
('Contacts.Router.Create', 0, 'Добавление Маршрутов', NULL, 'N;'),
('Contacts.Router.Update', 0, 'Редактирование Маршрутов', NULL, 'N;');
