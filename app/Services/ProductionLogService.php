<?php

namespace App\Services;

use App\Repositories\ProductionLogRepository;
use App\Repositories\RecipeRepository;

class ProductionLogService
{
    protected $logRepo;
    protected $recipeRepo;

    public function __construct(ProductionLogRepository $logRepo, RecipeRepository $recipeRepo)
    {
        $this->logRepo = $logRepo;
        $this->recipeRepo = $recipeRepo;
    }

    public function getAllLogs($perPage = 20)
    {
        return $this->logRepo->getAll($perPage);
    }

    public function getLogById($id)
    {
        return $this->logRepo->findById($id);
    }

    public function createLog(array $data)
    {
        $recipe = $this->recipeRepo->findById($data['recipe_id']);
        $data['item_id'] = $recipe->item_id;
        $data['qty_produced'] = $data['batches'] * $recipe->batch_qty;
        $data['created_by'] = auth()->id();
        return $this->logRepo->create($data);
    }
}
