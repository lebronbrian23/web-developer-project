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
        $stmt = $this->db->prepare("INSERT INTO jobs (title, script, country, state_or_province, reference_file_path, budget, ip_address) VALUES (:title, :script, :country, :state_or_province, :reference_file_path, :budget, :ip_address)");

        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':script', $data['script'] ?? null);
        $stmt->bindValue(':country', $data['country']);
        $stmt->bindValue(':state_or_province', $data['state_or_province']);
        $stmt->bindValue(':reference_file_path', $data['reference_file_path'] ?? null);
        $stmt->bindValue(':budget', $data['budget']);
        $stmt->bindValue(':ip_address', $data['ip_address']);

        try {
            $result = $stmt->execute();

            if ($result) {
                $lastId = (int) $this->db->lastInsertId();
                return $lastId;
            } else {
                throw new \Exception("Execute returned false");
            }
        } catch (\Exception $e) {
            log_database("INSERT FAILED - " . $e->getMessage(), false, [
                'data' => $data,
                'error_info' => $stmt->errorInfo()
            ]);

            throw $e;
        }
    }

    // Fetch a job entry by its ID
    public function fetchById($id)
    {
        $stmt = $this->db->prepare("SELECT title, script, country, state_or_province, reference_file_path, budget, ip_address, created_at, updated_at FROM jobs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
