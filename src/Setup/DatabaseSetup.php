<?php

namespace Fenner\Blog\Setup;

use Fenner\Blog\Setup\Database;
use PDOException;

class DatabaseSetup {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createTables() {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS roles (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(50) NOT NULL
                );

                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    role_id INT,
                    FOREIGN KEY (role_id) REFERENCES roles(id)
                );

                CREATE TABLE IF NOT EXISTS blogs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    content TEXT NOT NULL,
                    user_id INT,
                    FOREIGN KEY (user_id) REFERENCES users(id)
                );

                CREATE TABLE IF NOT EXISTS comments (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    content TEXT NOT NULL,
                    user_id INT,
                    blog_id INT,
                    FOREIGN KEY (user_id) REFERENCES users(id),
                    FOREIGN KEY (blog_id) REFERENCES blogs(id)
                );
            ");
            echo "Tables created successfully.\n";
        } catch (PDOException $e) {
            echo "Error creating tables: " . $e->getMessage() . "\n";
        }
    }
}
