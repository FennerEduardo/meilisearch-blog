<?php

namespace Fenner\Blog\Services;

use MeiliSearch\Client;

class GlobalSearch
{
    private $meiliSearch;

    public function __construct()
    {
        $this->meiliSearch = new Client('http://localhost:7700', 'KHKwqv-3awFLRvtjHUSexQDDG9sZFIUiQInYkTPonCY');
    }

    public function searchByIndexAndPrimaryKey($indexName, $primaryKey)
    {
        $index = $this->meiliSearch->index($indexName);
        $document = $index->getDocument($primaryKey);
        return $document;
    }

    public function getAllDocumentsFromIndex($indexName, $query, $perPage = 20, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        $results = $this->meiliSearch->index($indexName)->search($query, [
            'limit' => $perPage,
            'offset' => $offset
        ]);
        $hits = $results->getHits();

        $this->meiliSearch->index($indexName)->updateSettings([
            'pagination' => [
                'maxTotalHits' => 500
            ]
        ]);
        $settings = $this->meiliSearch->index($indexName)->getSettings();

        return [
            'hits' => $hits,
            'settings' => $settings
        ];
    }

    public function findById($indexName, $id)
    {
        $this->meiliSearch->index($indexName)->updateFilterableAttributes(['id']);
        $results = $this->meiliSearch->index($indexName)->search('', [
            'filter' => 'id = ' . $id
        ]);
        return $results->getHits();
    }
}
