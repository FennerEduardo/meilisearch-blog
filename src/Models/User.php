<?php

namespace Fenner\Blog\Models;

use Fenner\Blog\Setup\Database;
use PDO;
use MeiliSearch\Client;

class User extends Model
{
    public $db;
    public $meiliSearch;
    public $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->meiliSearch = new Client('http://localhost:7700', 'KHKwqv-3awFLRvtjHUSexQDDG9sZFIUiQInYkTPonCY');
        $this->indexName = 'users';
    }

    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function indexUsers()
    {
        $users = $this->getAllUsers();
        $this->meiliSearch->index('users')->addDocuments($users, 'id');
    }

    public function createUser($data) {
        return $this->create($this->table, $data);
    }

    public function updateUser($id, $data) {
        $this->update($this->table, $data, $id);
    }
}
