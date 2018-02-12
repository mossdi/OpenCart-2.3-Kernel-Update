<?php
// Heading
$_['heading_title']               = 'Генератор ЧПУ';
$_['text_success_seo_generation'] = 'ЧПУ успешно сгенерированы, нажмите сохранить, чтобы они были присвоены';
$_['text_success']                = 'ЧПУ успешно присвоены';
$_['text_success_']               = 'ЧПУ успешно присвоены';
$_['button_save']                 = 'Сохранить ЧПУ';
$_['button_setting']              = 'Сохранить настройки';
$_['button_seo_generate']         = 'Генерировать ЧПУ, но не сохранять';
$_['button_filter']               = 'Искать';
$_['error_permission']            = 'У Вас нет прав для изменения товаров!';
$_['error_dublicates']            = 'Проверьте форму, у некоторых ЧПУ есть дубликаты';
$_['error_selected']              = 'Вы не указали позиции';
$_['error_seo_generation']        = 'Новых ЧПУ не создано';
$_['text_seo_generate']           = ' - для пустых ЧПУ будут сгенерированы уникальные ЧПУ. Для сохранения не забудьте нажать "Сохранить"';
$_['text_seo_products']           = 'Продукты';
$_['text_seo_categories']         = 'Категории';
$_['text_seo_manufactures']       = 'Производители';
$_['column_name']                 = 'Название';
$_['column_keyword']              = 'ЧПУ';
$_['column_id']                   = 'ID';
$_['text_no_results']             = 'Нет добавленных данных для создания ЧПУ';
$_['text_module']                 = 'Модули';
//v.1.1
$_['text_seo_informations']       = 'Статьи';
$_['text_only_to_latin']          = '<b>Всегда преобразовать в латинские буквы</b>';
$_['text_canonical_products']     = '<b>Включить канонические ЧПУ продуктам?</b><br>У всех продуктов будут уникальные урлы, включая путь к категории, в которой расположен продукт. Если продукт расположен в нескольких категориях, то будет выбран самый длинный путь. Если раньше сайт был проиндексирован с другими путями, то по всем старым ссылкам будет установлен 301 редирект на канонический урл';

//v.1.2
$_['button_back']                            = 'Назад';
$_['text_seo_simpleblogarticles']            = 'Статьи Simple Blog';
$_['text_seo_simpleblogcategories']          = 'Категории Simple Blog';
$_['text_remove_seorl']                      = 'Очистить, но не сохранять';
$_['text_enabled']                           = 'Включено';
$_['text_disabled']                          = 'Выключено';
$_['text_success_setting']                   = 'Настройки успешно сохранены';
$_['entry_status']                           = '<b>Включить автогенерацию SEO URL в товарах, категориях, производителях?</b><br>При выборе "Включено", ЧПУ будут создаваться, только если ранее ЧПУ у данного товара, категории, производителя отсутствовало. Создаваться ЧПУ будет при создании названия товара, категории, производителя';
$_['error_notifications']                    = 'Ошибка проверки бесплатного обновления. Обновления доступны на сервере: www.ocext.com';
$_['text_select_main_category']              = 'Использовать для канонических урлов главную категорию (должно быть включено в сборке, в карточке товара)?';

$_['text_breadcrumb_list']                   = '<b>Включить микроразметку для навигации (в формате JSON-LD)</b><br><a href="https://schema.org/BreadcrumbList" target="_blank">Микроразметка</a> для хлебных крошек, улучшает ранжирование страниц в поисковиках. И рекомендована в 2014 году для всех вэб-сайтов';
$_['text_breadcrumb_list_error']             = 'Не удалось создать папку для записи JS, в которых будет размещаться JSON-LD. Создайте папку <b>jsonldmicrodata</b> с правами на запись (CMOD) - 7777, самостоятельно по адресу: '.HTTP_CATALOG.'/catalog/view/javascript/<b>jsonldmicrodata</b>';
$_['text_product_microdata_status']          = '<b>Включить микроразметку для товаров (в формате JSON-LD - создает расширенные сниппеты в выдаче)</b><br><a href="https://developers.google.com/structured-data/rich-snippets/products#single_product_page" target="_blank">Микроразметка</a> для товаров, улучшает ранжирование страниц в поисковиках. И рекомендована в 2014 году для всех вэб-сайтов';
$_['text_product_microdata_image']           = 'Передавать изображение?';
$_['text_product_microdata_brand']           = 'Передавать производителя?';
$_['text_product_microdata_aggregateRating'] = 'Передавать в микроразметку суммарный рейтинг товара?';
$_['text_product_microdata_review']          = 'Передавать отзывы (не более 5-ти последних)?';
$_['text_product_microdata_offerCount']      = 'Передавать количество товара?';
$_['text_product_microdata_availability']    = 'Передавать статус в наличии всем товарам (если выключить, никакой статус не будет передаваться)?';
$_['text_product_microdata_settings']        = 'Дополнительные настройки';
$_['text_product_microdata_priceCurrency']   = 'Валюта (обязательно)';
$_['tab_welcome_extecom']                    = 'Информация и поддержка';