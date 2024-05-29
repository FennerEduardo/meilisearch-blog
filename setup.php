<?php

use Fenner\Blog\Setup\DatabaseSeeder;
use Fenner\Blog\Setup\DatabaseSetup;

require __DIR__ . '/vendor/autoload.php';



// Crear tablas
$databaseSetup = new DatabaseSetup();
$databaseSetup->createTables();

// Llenar con datos de prueba
$databaseSeeder = new DatabaseSeeder();
$databaseSeeder->seedData();
