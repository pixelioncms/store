
INSERT INTO `{prefix}shop_category` (`id`, `lft`, `rgt`, `level`, `seo_alias`, `full_path`, `image`, `switch`) VALUES
(2, 2, 9, 2, 'kategorija_level_1', 'kategorija_level_1', '', 1),
(3, 10, 11, 2, 'kategorija_level_2', 'kategorija_level_2', '', 1),
(4, 12, 15, 2, 'kategorija_level_3', 'kategorija_level_3', '', 1),
(5, 7, 8, 3, 'kategorija_level_1_1', 'kategorija_level_1/kategorija_level_1_1', '', 1),
(6, 5, 6, 3, 'kategorija_level_1_2', 'kategorija_level_1/kategorija_level_1_2', '', 1),
(7, 3, 4, 3, 'kategorija_level_1_3', 'kategorija_level_1/kategorija_level_1_3', '', 1),
(8, 13, 14, 3, 'kategorija_level_3_1', 'kategorija_level_3/kategorija_level_3_1', '', 1);


INSERT INTO `{prefix}shop_category_translate` (`id`, `object_id`, `language_id`, `name`, `description`) VALUES
(2, 2, 1, 'Категория level 1', ''),
(3, 3, 1, 'Категория level 2', ''),
(4, 4, 1, 'Категория level 3', ''),
(5, 5, 1, 'Категория level 1-1', ''),
(6, 6, 1, 'Категория level 1-2', ''),
(7, 7, 1, 'Категория level 1-3', ''),
(8, 8, 1, 'Категория level 3-1', '');

INSERT INTO `{prefix}shop_currency` (`id`, `name`, `iso`, `symbol`, `rate`, `rate_old`, `main`, `default`) VALUES
(1, 'Гривна', 'UAH', 'грн.', 1.000, NULL, 1, 1),
(2, 'Доллар', 'USD', '$', 25.000, NULL, 0, 0);


INSERT INTO `{prefix}shop_product_type` (`name`, `categories_preset`, `main_category`) VALUES
('General', NULL, 0);

INSERT INTO `{prefix}shop_attribute` (`id`, `group_id`, `name`, `type`, `display_on_front`, `use_in_filter`, `use_in_variants`, `use_in_compare`, `select_many`, `ordern`, `required`) VALUES
(1, NULL, 'size', 4, 1, 1, 1, 0, 1, 1, 0),
(2, NULL, 'country', 5, 1, 1, 1, 0, 1, 2, 0);

INSERT INTO `{prefix}shop_attribute_option` (`id`, `attribute_id`, `ordern`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 5),
(6, 2, 6),
(7, 2, 7),
(8, 2, 8);

INSERT INTO `{prefix}shop_attribute_option_translate` (`id`, `language_id`, `object_id`, `value`) VALUES
(1, 1, 1, 'S'),
(2, 1, 2, 'M'),
(3, 1, 3, 'L'),
(4, 1, 4, 'XXS'),
(5, 1, 5, 'Украина'),
(6, 1, 6, 'Испания'),
(7, 1, 7, 'Германия'),
(8, 1, 8, 'США');


INSERT INTO `{prefix}shop_attribute_translate` (`id`, `object_id`, `language_id`, `title`, `abbreviation`) VALUES
(1, 1, 1, 'Размер', ''),
(2, 2, 1, 'Страна', '');