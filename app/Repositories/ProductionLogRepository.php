<?php

namespace App\Repositories;

use App\Models\ProductionLog;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\DB;

class ProductionLogRepository
{
    protected $stockLedgerService;

    public function __construct(StockLedgerService $stockLedgerService)
    {
        $this->stockLedgerService = $stockLedgerService;
    }

    public function getAll($perPage = 20)
    {
        return ProductionLog::with(['recipe.item', 'item', 'createdBy'])->orderByDesc('production_date')->paginate($perPage);
    }

    public function findById($id)
    {
        return ProductionLog::with(['recipe', 'item'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $log = ProductionLog::create($data);
            $recipe = $log->recipe;
            $batches = $log->batches;
            $qtyProduced = $log->qty_produced;

            foreach ($recipe->ingredients as $ingredient) {
                $totalQtyOut = $ingredient->qty_required * $batches;
                $this->stockLedgerService->recordOutbound(
                    $ingredient->ingredient_item_id,
                    'production_out',
                    'production_log',
                    $log->id,
                    $totalQtyOut
                );
            }

            $this->stockLedgerService->recordInbound(
                $log->item_id,
                'production_in',
                'production_log',
                $log->id,
                $qtyProduced
            );

            return $log;
        });
    }

    public function delete($id)
    {
        $log = $this->findById($id);
        // We may not allow deletion of production logs for audit. But if needed, we should reverse stock entries.
        // For now, disable deletion.
        throw new \Exception('Production logs cannot be deleted. Use adjustment if needed.');
    }
}