<?php

/**
 * Message translations.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package modules.messages.ru
 */
return array(
    'PRODUCT_RELATED_BILATERAL' => 'Активировать двустороннюю связь между товарами',
    'AUTO_ADD_SUBCATEGORIES' => 'Автоматически размещать товар во всех предков категории',
    'AUTO_FILL_SHORT_DESC' => 'Автоматически заполнять краткое описание "Характеристиками"',
    'AJAX_MODE' => 'Режим AJAX',
    'HINT_AJAX_MODE' => 'В этом режиме магазин будет максимально переключатся быстрее',

    'AUTO_GEN_PRODUCT_TITLE' => 'Автоматически генерировать заголовок и ссылку у товара',
    'AUTO_GEN_CAT_META' => 'Автоматическая генерация метаданных категорий',
    'AUTO_GEN_CAT_TPL_TITLE' => 'Шаблон заголовка для категории',
    'AUTO_GEN_CAT_TPL_KEYWORDS' => 'Шаблон ключевых слов для категории',
    'AUTO_GEN_CAT_TPL_DESCRIPTION' => 'Шаблон мета описание для категории',
    'AUTO_GEN_META' => 'Автоматическая генерация метаданных товара',
    'AUTO_GEN_TPL_TITLE' => 'Шаблон заголовка для товара',
    'AUTO_GEN_TPL_KEYWORDS' => 'Шаблон ключевых слов для товара',
    'AUTO_GEN_TPL_DESCRIPTION' => 'Шаблон мета описание для товара',
    'PER_PAGE' => 'Количество товаров на сайте',
    'PATH' => 'Путь сохранения',
    'THUMBPATH' => 'Путь к превью',
    'URL' => 'Ссылка к изображениям',
    'THUMBURL' => 'Ссылка к превью',
    'MAXFILESIZE' => 'Максимальный размер файла',
    'MAXIMUM_IMAGE_SIZE' => 'Максимальный размер изображения',
    'HINT_PER_PAGE' => 'Вы можете указать несколько значений разделяя их запятой. Например: 10,20,30',
    'HINT_AUTO_GEN_URL' => '<p><code>{main_category}</code> - Название категории.</p><p><code>{sub_category_name}</code> - Название предка категории.</p><p><code>{manufacturer}</code> - Производитель.</p><p><code>{product_sku}</code> - Атрикул.</p>',
    'TAB_IMG' => 'Изображения',
    'TAB_SEO' => 'SEO товаров',
    'TAB_CAT_SEO' => 'SEO категорий',
    'TAB_GENERAL' => 'Основные',
    'TAB_AUTOLABELS' => 'Лайбы',
    'LABEL_TOPBUY' => 'Считать товар "топ продаж" от (N) заказов',
    'LABEL_POPULAR' => 'Считать товар "популярный" от (N) просмотров',
    'LABEL_SALE' => 'Считать топ продаж',
    'LABEL_NEW_DAYS' => 'Считать товар новым (N) дней',
    'FILTER_ENABLE_PRICE' => 'Активировать фильтр диапазона цен?',
    'FILTER_ENABLE_BRAND' => 'Активировать фильтр производителей?',
    'FILTER_ENABLE_ATTR' => 'Активировать фильтр атрибутов?',
    'CREATE_BTN_ACTION' => 'Создавать товар сразу с типом товара.',
    'HINT_CREATE_BTN_ACTION' => 'Если Вы используете один "Тип товара", то вы можете привязать кнопку к этому типу.<br/>Если Вы используете несколько "Типов товаров" - выберете "Не привязывать."',
    'META_TPL' => '<p><code>{product_name}</code> - Название товара.</p><p><code>{product_price}</code> - Цена товара.</p>        <p><code>{product_sku}</code> - Артикул товара.</p>        <p><code>{product_brand}</code> - Производитель товара.</p>        <p><code>{product_main_category}</code> - Главная категория товара.</p>        <p><code>{current_currency}</code> - Текущая валюта ({currency}).</p>',
    'META_CAT_TPL' => '<p><code>{category_name}</code> - Название категории.</p><p><code>{sub_category_name}</code> - Название предка категории.</p>        <p><code>{current_currency}</code> - Текущая валюта ({currency}).</p>',
);