<?php

namespace Fenner\Blog\Controllers;

use Fenner\Blog\Models\Blog;
use Fenner\Blog\Services\GlobalSearch;

class BlogController
{
    private $blogModel;

    public function __construct()
    {
        $this->blogModel = new Blog();
        // $this->blogModel->indexBlogs();
    }

    public function index()
    {
        $startTime = microtime(true);

        $blogs = $this->blogModel->getAllBlogsWithCommentsAndUserRoles();

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        $response = [
            'execution_time' => $executionTime,
            'data' => $blogs
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function indexBySearch()
    {
        $startTime = microtime(true);
        
        $query = isset($_GET['search']) ? $_GET['search'] : '';
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $globalSearch = new GlobalSearch();
        $blogs = $globalSearch->getAllDocumentsFromIndex('blogs', $query, $perPage, $page);

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        $response = [
            'execution_time' => $executionTime,
            'data' => $blogs,
            'current_page' => $page,
            'per_page' => $perPage
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
