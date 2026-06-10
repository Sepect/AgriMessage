<?php

namespace App\Services;

use App\Repositories\FarmerRepository;

class FarmerService
{
    protected $farmerRepository;

    public function __construct(FarmerRepository $farmerRepository)
    {
        $this->farmerRepository = $farmerRepository;
    }

    public function registerFarmer(array $data)
    {
        // Business logic, validation, etc.
        return $this->farmerRepository->create($data);
    }
}
