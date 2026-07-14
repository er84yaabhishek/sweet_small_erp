<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
    protected function modelClass(): string
    {
        return Customer::class;
    }

    public function getAll($perPage = 20)
    {
        return Customer::orderBy('name')->paginate($perPage);
    }

    public function getAllCustomers()
    {
        return Customer::orderBy('name')->get();
    }
}
