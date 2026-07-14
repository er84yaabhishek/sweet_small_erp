<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    protected function modelClass(): string
    {
        return Category::class;
    }

    public function getAll($perPage = 20)
    {
        return Category::orderBy('name')->paginate($perPage);
    }

    public function getByType($type) // 'finished_good' or 'raw_material'
    {
        return Category::where('type', $type)->orderBy('name')->get();
    }
}