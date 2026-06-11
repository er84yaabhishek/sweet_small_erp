<?php

namespace App\Repositories;

use App\Models\Expense;

class ExpenseRepository
{
    public function getAll($perPage = 20, $filters = [])
    {
        $query = Expense::with('createdBy');
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('expense_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('expense_date', '<=', $filters['to_date']);
        }
        return $query->orderByDesc('expense_date')->paginate($perPage);
    }

    public function findById($id)
    {
        return Expense::findOrFail($id);
    }

    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function update($id, array $data)
    {
        $expense = $this->findById($id);
        $expense->update($data);
        return $expense;
    }

    public function delete($id)
    {
        $expense = $this->findById($id);
        return $expense->delete();
    }
}