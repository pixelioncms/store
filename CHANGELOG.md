# CMS Change Log

## Plans, Ideas, etc.
1. нужно добавтить в order_product image/base64
2. добавить миграции и сделать чтобы работали через веб-интерфейс.
3. добавить в виджет Обратный звоник, базу данных для учета + подставка полей по айпи
4. добавить в товар main_category_id = делать выборку в фильтрах по нему (убирается join)

### Version 1.0.1 RC under development
1. Remove: ShopProduct::formatPrice(). New use Yii::app()->currency->number_format()
2. Add: Html::viber()
3. Поправлена сортировка по цене, если товар привязан к валюте. теперь учитывается правильно и сортирвуется

### Version 1.0.0 RC (2019/02/2)