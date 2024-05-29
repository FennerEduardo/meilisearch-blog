<?php

namespace Fenner\Blog\Setup;

use PDO;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {    
        $dsn = "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=blog;charset=utf8mb4";
        $this->pdo = new PDO($dsn, 'fenner', 'fenner');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
