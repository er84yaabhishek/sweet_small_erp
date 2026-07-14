<?php

namespace App\Repositories;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\DB;

class PurchaseRepository
{
    protected $stockLedgerService;

    public function __construct(StockLedgerService $stockLedgerService)
    {
        $this->stockLedgerService = $stockLedgerService;
    }

    public function getAll($perPage = 20, $filters = [])
    {
        $query = Purchase::with(['supplier', 'createdBy']);
        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('purchase_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('purchase_date', '<=', $filters['to_date']);
        }
        return $query->orderByDesc('purchase_date')->paginate($perPage);
    }

    public function findById($id)
    {
        return Purchase::with(['supplier', 'items.item', 'createdBy'])->findOrFail($id);
    }

    public function create(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            $purchase = Purchase::create($data);
            foreach ($items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'tax_amount' => $item['tax_amount'] ?? 0,
                    'line_total' => $item['line_total'],
                ]);
                $this->stockLedgerService->recordInbound(
                    $item['item_id'],
                    'purchase',
                    'purchase',
                    $purchase->id,
                    $item['qty'],
                    $item['unit_price']
                );
            }

            return $purchase;
        });
    }

    public function update($id, array $data)
    {
        $purchase = $this->findById($id);
        $purchase->update($data);
        return $purchase;
    }

    public function delete($id)
    {
        $purchase = $this->findById($id);
        // Stock ledger entries will be deleted manually? Actually keep for audit, but we can mark?
        // Better to not delete purchases, only cancel with status. We'll avoid hard delete.
        return $purchase->delete();
    }
}