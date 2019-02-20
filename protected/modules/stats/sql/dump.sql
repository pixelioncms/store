CREATE TABLE IF NOT EXISTS `{prefix}main_history` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `dt` char(10) DEFAULT NULL,
  `hosts` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `search` int(11) DEFAULT NULL,
  `other` int(11) DEFAULT NULL,
  `fix` int(11) DEFAULT NULL,
  PRIMARY KEY (`i`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}mainp` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `dt` char(2) DEFAULT NULL,
  `god` char(4) DEFAULT NULL,
  `hosts` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `search` int(11) DEFAULT NULL,
  `other` int(11) DEFAULT NULL,
  `fix` int(11) DEFAULT NULL,
  PRIMARY KEY (`i`)
) ENGINE={engine} DEFAULT CHARSET={charset};

CREATE TABLE IF NOT EXISTS `{prefix}surf` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `day` char(3) DEFAULT NULL,
  `dt` date DEFAULT NULL,
  `tm` char(5) DEFAULT NULL,
  `refer` text,
  `ip` char(64) DEFAULT NULL,
  `proxy` char(64) DEFAULT NULL,
  `host` char(64) DEFAULT NULL,
  `lang` char(2) DEFAULT NULL,
  `user` text,
  `req` text,
  PRIMARY KEY (`i`)
) ENGINE={engine} DEFAULT CHARSET={charset};