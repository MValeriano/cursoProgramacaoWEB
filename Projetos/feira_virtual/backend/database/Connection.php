<?php

class Connection {

    private static $instance = null;

    public static function getConnection() {
        if (!self::$instance) {
            // POSTGRESQL
            /*$host = "localhost";
            $port = "5432";
            $dbname = "seubanco";
            $user = "postgres";
            $pass = "postgres";

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);*/

            // SQLITE
            $dbFile = __DIR__ . '/feira_virtual.sqlite';

            $dsn = "sqlite:" . $dbFile;

            self::$instance = new PDO($dsn);
           
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->exec("PRAGMA foreign_keys = ON;");
        }

        return self::$instance;
    }
}
