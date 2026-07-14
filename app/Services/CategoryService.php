<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $category = $this->categoryRepo->create($data);
            DB::commit();
            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCategory($id, array $data)
    {
        DB::beginTransaction();
        try {
            $category = $this->categoryRepo->update($id, $data);
            DB::commit();
            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteCategory($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->categoryRepo->delete($id);
            DB::commit();
            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}