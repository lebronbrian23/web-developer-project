<?php

namespace App\Controllers;

use App\Services\Csrf;
use App\Services\JobService;
use App\Services\Validator;
use App\Services\Mailer;
use App\Services\FileUpload;
use App\Services\Logger;

class JobFormController
{
    private $csrf;
    private $model;
    private $validator;
    private $mailer;
    private $file_upload;

    public function __construct(Csrf $csrf, JobService $model, Validator $validator, Mailer $mailer, FileUpload $file_upload)
    {
        $this->csrf = $csrf;
        $this->model = $model;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->file_upload = $file_upload;
    }

    // Method to display the job submission form
    public function showForm()
    {
        $csrfToken = $this->csrf->generateCsrfToken();

        // Check for a success submission ID passed via redirect
        $successId = isset($_GET['submitted']) ? $_GET['success_id'] : null;
        $submission = null;

        if ($successId) {
            // fetch the submission details from the database
            $submission = $this->model->getJobById($successId);
        }

        // Fetch provinces for the dropdown
        $provinces = $this->validator->getProvinces();
        $errors = [];
        $old_input = [];

        $this->renderView('layout', compact('csrfToken', 'submission', 'provinces', 'errors', 'old_input', 'successId'));
    }

    // Method handling the form submission,
    // validating the input, and saving the job data to the database.
    public function store()
    {
        $timestamp = date('Y-m-d H:i:s');

        // Validate CSRF token
        $this->csrf->verifyCsrfToken($_POST['csrf_token'] ?? '');

        // validate honeypot field (should be empty)
        // Silently reject without giving the bot any feedback that it was caught.
        if (!empty($_POST['website'])) {
            log_error('Bot submission detected', $_POST);
            return;
        }

        // Collect and sanitize raw input
        $input = [
            'title' => trim($_POST['title'] ?? ''),
            'script' => trim($_POST['script'] ?? ''),
            'country' => trim($_POST['country'] ?? ''),
            'state_or_province' => trim($_POST['state_or_province'] ?? ''),
            'budget' => trim($_POST['budget'] ?? ''),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'reference_file_path' =>
                !empty($_FILES['reference_file_path']['name']) ? $_FILES['reference_file_path']['name'] : null,
        ];

        // Log form submission received
        log_form(section('FORM SUBMISSION RECEIVED'));
        log_form("POST Data: " . Logger::data($_POST));
        log_form("FILES Data: " . Logger::data($_FILES));
        log_form("Processed Input: " . Logger::data($input));

        // Validate input data including file uploads
        $isValid = $this->validator->validate($input, $_FILES);

        if (!$isValid) {
            log_form(section('VALIDATION ERRORS'), [
                'errors' => $this->validator->errors(),
                'input' => $input
            ]);

            // If there are validation errors, re-render the form with error messages
            $csrfToken = $this->csrf->generateCsrfToken();
            $provinces = $this->validator->getProvinces();
            $submission = null;
            $successId = null;
            $errors = $this->validator->errors();
            $old_input = $input;

            $this->renderView('layout', compact('csrfToken', 'errors', 'old_input', 'provinces', 'submission', 'successId'));

            return;
        }

        // Handle optional file upload using the FileUpload service
        $uploadedFilePath = null;
        if (!empty($_FILES['reference_file_path']['name'])) {
            try {
                $uploadedFilePath = $this->file_upload->storeFile($_FILES['reference_file_path']);
                $input['reference_file_path'] = $uploadedFilePath;
            } catch (\Exception $e) {
                log_error('File upload error', ['message' => $e->getMessage()]);
            }
        }

        // Save the job data to the database
        $successId = $this->model->createJob($input);

        // Fetch submission details and send email
        $submissionDetails = $this->model->getJobById($successId);

        log_form(section('FORM SUBMISSION SAVED'), $submissionDetails);

        if ($submissionDetails) {
            $this->mailer->sendConfirmationEmail($submissionDetails);
        }

        // Redirect to success view with the submission ID
        // Prevents duplicate submissions on page refresh
        header("Location: ?submitted=1&success_id={$successId}");
        exit();
    }

    // Method to render views and pass data to them
    private function renderView(string $view, array $data = [])
    {
        // Make data available to helper functions via $GLOBALS
        $GLOBALS['errors'] = $data['errors'] ?? [];
        $GLOBALS['old_input'] = $data['old_input'] ?? [];

        extract($data);
        include __DIR__ . '/../Views/' . $view . '.php';
    }
}
