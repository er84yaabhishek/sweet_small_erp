<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getAllCategories($perPage = 20)
    {
        return $this->categoryRepo->getAll($perPage);
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepo->findById($id);
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepo->create($data);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepo->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepo->delete($id);
    }
}