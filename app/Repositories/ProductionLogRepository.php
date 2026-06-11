<?php

namespace App\Repositories;

use App\Models\ProductionLog;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

class ProductionLogRepository
{
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
        DB::beginTransaction();
        try {
            $log = ProductionLog::create($data);
            $recipe = $log->recipe;
            $batches = $log->batches;
            $qtyProduced = $log->qty_produced;

            // Stock OUT for each raw material
            foreach ($recipe->ingredients as $ingredient) {
                $totalQtyOut = $ingredient->qty_required * $batches;
                StockLedger::create([
                    'item_id' => $ingredient->ingredient_item_id,
                    'txn_type' => 'production_out',
                    'reference_type' => 'production_log',
                    'reference_id' => $log->id,
                    'qty_in' => 0,
                    'qty_out' => $totalQtyOut,
                    'rate' => null, // cost may be derived from purchase average, but optional
                    'created_by' => auth()->id(),
                ]);
            }

            // Stock IN for finished good
            StockLedger::create([
                'item_id' => $log->item_id,
                'txn_type' => 'production_in',
                'reference_type' => 'production_log',
                'reference_id' => $log->id,
                'qty_in' => $qtyProduced,
                'qty_out' => 0,
                'rate' => null,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return $log;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $log = $this->findById($id);
        // We may not allow deletion of production logs for audit. But if needed, we should reverse stock entries.
        // For now, disable deletion.
        throw new \Exception('Production logs cannot be deleted. Use adjustment if needed.');
    }
}