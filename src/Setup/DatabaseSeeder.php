<?php

namespace Fenner\Blog\Setup;

use Fenner\Blog\Setup\Database;
use PDO;
use PDOException;

class DatabaseSeeder
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function seedData()
    {
        try {
            // Insert roles
            $this->db->exec("
                INSERT INTO roles (name) VALUES ('Admin'), ('User');
            ");

            // Insert users
            $this->db->exec("
                INSERT INTO users (username, email, password, role_id) VALUES
                ('admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', 1),
                ('user1', 'user1@example.com', '" . password_hash('user123', PASSWORD_BCRYPT) . "', 2),
                ('user2', 'user2@example.com', '" . password_hash('user123', PASSWORD_BCRYPT) . "', 2);
            ");

            // Insert blogs
            for ($i = 1; $i <= 10000; $i++) {
                $title = "Blog Post $i";
                $content = "This is the content of blog post number $i.";
                $userId = rand(1, 3); 

                $this->db->exec("
                    INSERT INTO blogs (title, content, user_id) VALUES
                    ('$title', '$content', $userId);
                ");
            }

            // Insert comments
            for ($i = 1; $i <= 15000; $i++) {
                $content = "Comment number $i";
                $userId = rand(1, 3); 
                $blogId = rand(1, 50); 

                $this->db->exec("
                    INSERT INTO comments (content, user_id, blog_id) VALUES
                    ('$content', $userId, $blogId);
                ");
            }


            echo "Data seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding data: " . $e->getMessage() . "\n";
        }
    }
}
