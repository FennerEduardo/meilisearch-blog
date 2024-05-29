<?php

namespace Fenner\Blog\Controllers;

use Fenner\Blog\Models\Role;

class RoleController {
    private $roleModel;

    public function __construct() {
        $this->roleModel = new Role();
    }

    public function index() {
        $roles = $this->roleModel->getAllRoles();
        header('Content-Type: application/json');
        echo json_encode($roles);
    }
}
