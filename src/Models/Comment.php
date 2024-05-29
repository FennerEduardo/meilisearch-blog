<?php

namespace Fenner\Blog\Models;

use Fenner\Blog\Setup\Database;
use PDO;

class Comment  extends Model
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllComments()
    {
        $stmt = $this->db->query("SELECT * FROM comments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
