<?php

/**
 * JobService
 *
 * Handles job-related operations and interactions with the repository.
 */


namespace App\Services;

use App\Repositories\JobRepository;
use App\Services\Validator;

class JobService
{
    private $repository;
    private $validator;

    public function __construct(JobRepository $repository, Validator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function createJob($data): int
    {
        return $this->repository->create($data);
    }

    public function getJobById($id): ?array
    {
        return $this->repository->fetchById($id);
    }
}
