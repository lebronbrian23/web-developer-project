<?php

use App\Controllers\JobFormController;
use App\Repositories\JobRepository;

require_once '../vendor/autoload.php';

require_once '../app/config.php';

require_once '../config/database.php';

session_start();

// Dependencies
$pdo = Database::getConnection();

$controller = new JobFormController();
$repository = new JobRepository($pdo);

// Routing logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
} else {
    $controller->showForm();
}