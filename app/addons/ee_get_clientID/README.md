ee_get_clientID - Модуль записывает clientID яндекса из COOKIES в доп. поле ee_clientID таблицы USERS, показывает в карточке заказа админ панели user_id и _ym_uid Перезапись _ym_uid происходит если пользователь зашёл на сайт
 и _ym_uid за время его отсутствия изменился.
Во время инсталяции модуль устанавливает дополнительный хук ee_rus_exim_1c_order_data в файл app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php и удалит его при удалении модуля, дополнительное поле ee_clientID
 не удаляется и данные сохраняются.
Хук испольхуется для передачи параметра ee_clientID в файл XML выгрузки Commerceml для 1с и МойСклад