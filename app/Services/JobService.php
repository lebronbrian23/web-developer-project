<?php 

namespace App\Services;

use App\Repositories\JobRepository;
use App\Services\Validator;

Class JobService
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
        // validate data
        $errors = $this->validator->validate($data);
        
        if (!empty($errors)) {
            return $errors;
        }

        return $this->repository->create($data);
    }
}