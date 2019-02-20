

INSERT INTO `{prefix}shop_currency` (`name`, `iso`, `symbol`, `rate`, `is_main`, `is_default`) VALUES
('Гривна', 'UAH', 'грн.', 1.000, '1', '1'),
('Доллар', 'USD', '$', 25.000, '0', '0');


INSERT INTO `{prefix}shop_product_type` (`name`, `categories_preset`, `main_category`) VALUES
('General', NULL, 0);

INSERT INTO `{prefix}shop_attribute` (`group_id`, `name`, `type`, `display_on_front`, `use_in_filter`, `use_in_variants`, `use_in_compare`, `select_many`, `ordern`, `required`) VALUES
(NULL, 'size', 4, 1, 1, 1, 0, 1, 1, 0),
(NULL, 'country', 5, 1, 1, 1, 0, 1, 2, 0);

INSERT INTO `{prefix}shop_attribute_option` (`attribute_id`, `ordern`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8);

INSERT INTO `{prefix}shop_attribute_option_translate` (`language_id`, `object_id`, `value`) VALUES
(1, 1, 'S'),
(1, 2, 'M'),
(1, 3, 'L'),
(1, 4, 'XXS'),
(1, 5, 'Украина'),
(1, 6, 'Испания'),
(1, 7, 'Германия'),
(1, 8, 'США');


INSERT INTO `{prefix}shop_attribute_translate` (`object_id`, `language_id`, `title`, `abbreviation`) VALUES
(1, 1, 'Размер', ''),
(2, 1, 'Страна', '');