<?php

namespace Fenner\Blog\Models;

use Fenner\Blog\Setup\Database;
use PDO;

class Role  extends Model
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllRoles()
    {
        $stmt = $this->db->query("SELECT * FROM roles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
