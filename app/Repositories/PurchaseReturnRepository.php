<?php

namespace App\Repositories;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Repositories\Contracts\ReturnRepository;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\DB;

class PurchaseReturnRepository implements ReturnRepository
{
    protected $stockLedgerService;

    public function __construct(StockLedgerService $stockLedgerService)
    {
        $this->stockLedgerService = $stockLedgerService;
    }

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
        return DB::transaction(function () use ($data, $items) {
            $return = PurchaseReturn::create($data);
            foreach ($items as $item) {
                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ]);
                $this->stockLedgerService->recordOutbound(
                    $item['item_id'],
                    'purchase_return',
                    'purchase_return',
                    $return->id,
                    $item['qty'],
                    $item['unit_price']
                );
            }

            return $return;
        });
    }
}