<?php

namespace Fenner\Blog\Models;

use Fenner\Blog\Setup\Database;
use PDO;
use MeiliSearch\Client;

class Blog  extends Model
{
    public $db;
    public $meiliSearch;
    public $table = 'blogs';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->meiliSearch = new Client('http://localhost:7700', 'dEFUtLYFq56xCWALmRgh1izkjjPcSBN3pkppMOunV6I');
        $this->indexName = 'blogs';
    }

    public function getAllBlogsWithCommentsAndUserRoles()
    {
        $sql = "
            SELECT 
                blogs.id AS id, 
                blogs.title AS blog_title, 
                blogs.content AS blog_content, 
                users.id AS user_id, 
                users.username AS user_username, 
                users.email AS user_email, 
                roles.name AS user_role,
                comments.id AS comment_id,
                comments.content AS comment_content
            FROM blogs
            LEFT JOIN users ON blogs.user_id = users.id
            LEFT JOIN roles ON users.role_id = roles.id
            LEFT JOIN comments ON blogs.id = comments.blog_id
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBlogs()
    {
        $stmt = $this->db->query("SELECT * FROM blogs");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function indexBlogs() {
        $blogs = $this->getAllBlogsWithCommentsAndUserRoles();
        $documents = [];
        foreach ($blogs as $blog) {
            $documents[$blog['id']]['id'] = $blog['id'];
            $documents[$blog['id']]['blog_title'] = $blog['blog_title'];
            $documents[$blog['id']]['blog_content'] = $blog['blog_content'];
            $documents[$blog['id']]['user'] = [
                'user_id' => $blog['user_id'],
                'user_username' => $blog['user_username'],
                'user_email' => $blog['user_email'],
                'user_role' => $blog['user_role'],
            ];
            $documents[$blog['id']]['comments'][] = [
                'comment_id' => $blog['comment_id'],
                'comment_content' => $blog['comment_content'],
            ];
        }

        $documents = array_values($documents);
        $index = $this->meiliSearch->getIndex('blogs');
        $index->addDocuments($documents, 'id');
    }
}
