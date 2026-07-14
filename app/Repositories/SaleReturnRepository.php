<?php

namespace App\Repositories;

use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

class SaleReturnRepository
{
    public function getAll($perPage = 20)
    {
        return SaleReturn::with(['customer', 'sale', 'createdBy'])->orderByDesc('return_date')->paginate($perPage);
    }

    public function findById($id)
    {
        return SaleReturn::with(['items.item', 'customer'])->findOrFail($id);
    }

    public function create(array $data, array $items)
    {
        DB::beginTransaction();
        try {
            $return = SaleReturn::create($data);
            foreach ($items as $item) {
                SaleReturnItem::create([
                    'sale_return_id' => $return->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                    'reason' => $item['reason'] ?? null,
                ]);

                // Stock ledger IN (return adds stock back)
                StockLedger::create([
                    'item_id' => $item['item_id'],
                    'txn_type' => 'sale_return',
                    'reference_type' => 'sale_return',
                    'reference_id' => $return->id,
                    'qty_in' => $item['qty'],
                    'qty_out' => 0,
                    'rate' => $item['unit_price'],
                    'created_by' => auth()->id(),
                ]);
            }
            DB::commit();
            return $return;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}