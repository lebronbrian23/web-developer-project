<?php


namespace App\Repositories;

use PDO;

class JobRepository
{
    private $db;

    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    // Create a new job entry in the database
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO jobs (title, script, country, state_or_province, reference_file_path, budget, ip_address ) VALUES (:title, :script, :country, :state_or_province, :reference_file_path, :budget, :ip_address)");

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':script', $data['script'] ?? null);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':state_or_province', $data['state_or_province']);
        $stmt->bindParam(':reference_file_path', $data['reference_file_path'] ?? null);
        $stmt->bindParam(':budget', $data['budget']);
        $stmt->bindParam(':ip_address', $data['ip_address']);

        return $stmt->execute();
    }
}