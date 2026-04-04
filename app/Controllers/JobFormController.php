<?php

namespace App\Controllers;

class JobFormController {

    public function showForm()
    {
        include '../app/Views/form.php';
    }

    public function store()
    {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        // Validate input
        if (empty($title) || empty($description)) {
            echo "Title and description are required.";
            return;
        }

        // Save to database
        $db = new Database();
        $db->insertJob($title, $description);

        echo "Job posted successfully!";
    }
    
}