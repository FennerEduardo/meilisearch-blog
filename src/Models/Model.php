<?php

namespace Fenner\Blog\Models;

use PDO;

class Model {
    public $db;
    public $meiliSearch;
    public $indexName;

    public function __construct(PDO $db, $meiliSearch, $indexName) {
        $this->db = $db;
        $this->meiliSearch = $meiliSearch;
        $this->indexName = $indexName;
    }

    public function create($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->db->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        $stmt->execute($data);
        $id = $this->db->lastInsertId();
        $this->indexRecord($id);

        return $id;
    }

    public function update($table, $data, $id) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setString = implode(', ', $set);

        $stmt = $this->db->prepare("UPDATE $table SET $setString WHERE id = :id");
        $data['id'] = $id;
        $stmt->execute($data);

        $this->indexRecord($id);
    }

    protected function indexRecord($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->indexName} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            $index = $this->meiliSearch->getIndex($this->indexName);
            $index->addDocuments([$record]);
        }
    }
}
