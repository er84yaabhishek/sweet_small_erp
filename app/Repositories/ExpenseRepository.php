<?php

namespace App\Repositories;

use App\Models\Expense;

class ExpenseRepository extends BaseRepository
{
    protected function modelClass(): string
    {
        return Expense::class;
    }

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
}