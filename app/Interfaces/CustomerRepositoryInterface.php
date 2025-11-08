<?php

namespace App\Interfaces;

interface CustomerRepositoryInterface
{
    public function findOrCreate(array $data);
}
