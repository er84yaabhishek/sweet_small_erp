<?php

namespace App\Services;

use App\Repositories\ExpenseRepository;

class ExpenseService
{
    protected $expenseRepo;

    public function __construct(ExpenseRepository $expenseRepo)
    {
        $this->expenseRepo = $expenseRepo;
    }

    public function getAllExpenses($perPage = 20, $filters = [])
    {
        return $this->expenseRepo->getAll($perPage, $filters);
    }

    public function getExpenseById($id)
    {
        return $this->expenseRepo->findById($id);
    }

    public function createExpense(array $data)
    {
        $data['created_by'] = auth()->id();
        return $this->expenseRepo->create($data);
    }

    public function updateExpense($id, array $data)
    {
        return $this->expenseRepo->update($id, $data);
    }

    public function deleteExpense($id)
    {
        return $this->expenseRepo->delete($id);
    }

    public function getCategories()
    {
        return [
            'Rent', 'Electricity', 'Water', 'Salary', 'Repair',
            'Marketing', 'Packing', 'Transport', 'Other'
        ];
    }
}