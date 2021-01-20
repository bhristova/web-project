<?php
declare(strict_types=1);


class Db {

    private PDO $connection;

    public function __construct() {

        $configs = include('config.php');
        
        $dbhost = $configs->DB_SERVERNAME;
        $dbName = $configs->DB_NAME;
        $userName = $configs->DB_USERNAME;
        $userPassword = $configs->DB_PASSWORD;

        $this->connection = new PDO("mysql:host=$dbhost;dbname=$dbName", $userName, $userPassword,
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
