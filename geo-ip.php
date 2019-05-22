<?php

namespace nyos\geo;

class Ip {

    public static function search(string $ip) {

        $pdoOption = array(
            PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );

        $SqlLiteFile = dirname(__FILE__) . '/db.sl3';

        if (!file_exists($SqlLiteFile))
            throw new \Exception('Не найден файл базы данных (' . $SqlLiteFile . ')');

        $db = new \PDO('sqlite:' . $SqlLiteFile, null, null, $pdoOption);

        // $ip = "112.91.31.28"; //IP-адрес для проверки
        // Преобразуем IP-адрес в нужный нам формат
        $ip = sprintf("%u", ip2long($ip));

        $sql = $db->prepare('SELECT `full_name`, `short_name`, `iso` FROM `Iptocountry` WHERE :ip >=`ip_from` AND :ip <=`ip_to` ;');
        $sql->execute(array(':ip' => sprintf("%u", ip2long($ip))));

        return $sql->fetch();
    }

}
