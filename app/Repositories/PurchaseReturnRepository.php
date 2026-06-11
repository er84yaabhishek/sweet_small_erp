<?php

namespace App\Repositories;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

class PurchaseReturnRepository
{
    public function getAll($perPage = 20)
    {
        return PurchaseReturn::with(['supplier', 'purchase', 'createdBy'])
            ->orderByDesc('return_date')
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return PurchaseReturn::with(['items.item', 'supplier'])->findOrFail($id);
    }

    public function create(array $data, array $items)
    {
        DB::beginTransaction();
        try {
            $return = PurchaseReturn::create($data);
            foreach ($items as $item) {
                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ]);
                // Add stock ledger entry (outgoing)
                StockLedger::create([
                    'item_id' => $item['item_id'],
                    'txn_type' => 'purchase_return',
                    'reference_type' => 'purchase_return',
                    'reference_id' => $return->id,
                    'qty_in' => 0,
                    'qty_out' => $item['qty'],
                    'rate' => $item['unit_price'],
                    'created_by' => auth()->id(),
                ]);
            }
            DB::commit();
            return $return;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}