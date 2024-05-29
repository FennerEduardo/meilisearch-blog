<?php

namespace Fenner\Blog\Controllers;

use Fenner\Blog\Models\User;
use PDO;

class UserController
{

    public function __construct()
    {
    }

    public function index()
    {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function create()
    {
        $userModel = new User();

        $userData = [
            'username' => 'Bart Simpson',
            'email' => 'bart@simpsons.com',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'role_id' => 2
        ];

        $newUserId = $userModel->createUser($userData);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'user created', 'id' => $newUserId]);
    }

    public function update()
    {
        $userModel = new User();
        $userData = [
            'username' => 'Tony Stark',
            'email' => 'updated_email@example.com'
        ];
        header('Content-Type: application/json');
        $userModel->updateUser(1, $userData);

        echo json_encode($userModel);
    }
}
