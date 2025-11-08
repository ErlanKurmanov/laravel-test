<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{

    public function findOrCreate(array $data)
    {
        return Customer::firstOrCreate(
            ['email' => $data['customer_email']],
            [
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
            ]
        );
    }
}
