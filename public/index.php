<?php

use App\Controllers\JobFormController;
use App\Repositories\JobRepository;
use App\Services\Csrf;
use App\Services\JobService;
use App\Services\Validator;
use App\Services\Mailer;
use App\Services\FileUpload;

require_once '../vendor/autoload.php';

require_once '../app/config.php';

require_once '../config/database.php';

session_start();

// Dependencies
$pdo = Database::getConnection();

// Instantiate services and repositories
$csrf = new Csrf();
$validator = new Validator();
$mailer = new Mailer();
$fileUpload = new FileUpload();
$repository = new JobRepository($pdo);
$jobService = new JobService($repository, $validator);

// Instantiate controller
$controller = new JobFormController($csrf, $jobService, $validator, $mailer, $fileUpload);

// Routing logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
} else {
    $controller->showForm();
}
