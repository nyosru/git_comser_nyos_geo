Russian

----- Установка ----- 
composer require nyos/geo-country-ip

----- Пример как ищем страну -----
$country = \Nyos\geo\IpCountry::search('123.123.123.55');

----- результат -----
$country = array( 
    `full_name` => 'Russian Federation' , 
    `short_name` = 'RUS' , 
    `iso` => 'RU' );