<?php

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

    public function createJob($data)
    {
        // validate data - returns boolean true/false
        if (!$this->validator->validate($data)) {
            return $this->validator->errors();
        }

        return $this->repository->create($data);
    }

    public function getJobById($id)
    {
        return $this->repository->fetchById($id);
    }
}
