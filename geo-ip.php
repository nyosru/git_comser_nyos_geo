<?php

namespace nyos\geo;

class Ip {

    public static function setup($db) {

        $sq = [];
        require dirname(__FILE__) . '/geo-ip.setup.db.php';

//        $sql = $db->prepare('BEGIN;');
//        $sql->execute();
        $db->beginTransaction();

        foreach ($sq as $k => $v) {

            try {

                echo '<hr>';
                // echo $v;
                // $sql = $db->prepare('SELECT `full_name`, `short_name`, `iso` FROM `Iptocountry` WHERE :ip >=`ip_from` AND :ip <=`ip_to` ;');

                echo substr($v, 0, 200);

                $db->query($v);
//                $sql = $db->prepare($v);
//                $sql->execute();
            } catch (\PDOException $ex) {

                echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
                . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
                . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
                . PHP_EOL . $ex->getTraceAsString()
                . '</pre>';
            }
        }
        $db->commit();

        // $sql = $db->prepare('COMMIT;');
        // $sql->execute();
    }

    public static function search(string $ip) {

        $pdoOption = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );
        $SqlLiteFile = dirname(__FILE__) . '/db.sl3';

        try {

            $db22 = new \PDO('sqlite:' . $SqlLiteFile, null, null, $pdoOption);
            $db22->exec('PRAGMA journal_mode=WAL;');
            $stat = $db22->query('SELECT COUNT(id) as kolvo FROM Iptocountry ;');
            $kolvo = $stat->fetch();
            // \f\pa($s);

        } catch (\PDOException $ex) {

//            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
//            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
//            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
//            . PHP_EOL . $ex->getTraceAsString()
//            . '</pre>';
            
            if( strpos($ex->getMessage(),'no such table: Iptocountry') !== false ){
                
                // echo __LINE__;
                self::setup($db22);

            }
            
        }

//        $db = new \PDO('sqlite:' . $SqlLiteFile, null, null, $pdoOption);
//        $db->exec('PRAGMA journal_mode=WAL;');

//        if (!file_exists($SqlLiteFile)) {
//
//            unlink($SqlLiteFile);
//            self::setup($db);
//
//            $db = null;
//
//            $db = new \PDO('sqlite:' . $SqlLiteFile, null, null, $pdoOption);
//            $db->exec('PRAGMA journal_mode=WAL;');
//        }
        //throw new \Exception('Не найден файл базы данных (' . $SqlLiteFile . ')');

        try {

            // $ip = "112.91.31.28"; //IP-адрес для проверки
            // Преобразуем IP-адрес в нужный нам формат
            $ip = sprintf("%u", ip2long($ip));

            // $sql = $db->prepare();
            $sql = $db22->query('SELECT `full_name`, `short_name`, `iso` FROM `Iptocountry` WHERE '.sprintf("%u", ip2long($ip)).' >=`ip_from` AND '.sprintf("%u", ip2long($ip)).' <=`ip_to` ;' );
            //$db = null;
            
            return $sql->fetch();
            
        } catch (\PDOException $ex) {

            echo '<pre>--- ' . __FILE__ . ' ' . __LINE__ . '-------'
            . PHP_EOL . $ex->getMessage() . ' #' . $ex->getCode()
            . PHP_EOL . $ex->getFile() . ' #' . $ex->getLine()
            . PHP_EOL . $ex->getTraceAsString()
            . '</pre>';


            return false;
        }
    }

}
