<?php

namespace Fenner\Blog\Controllers;

use Fenner\Blog\Models\Comment;

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    public function index() {
        $comments = $this->commentModel->getAllComments();
        header('Content-Type: application/json');
        echo json_encode($comments);
    }
}
