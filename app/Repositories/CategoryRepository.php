<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll($perPage = 20)
    {
        return Category::orderBy('name')->paginate($perPage);
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->findById($id);
        return $category->delete();
    }

    public function getByType($type) // 'finished_good' or 'raw_material'
    {
        return Category::where('type', $type)->orderBy('name')->get();
    }
}