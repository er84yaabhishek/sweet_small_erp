<?php

namespace App\Repositories;

use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Repositories\Contracts\ReturnRepository;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\DB;

class SaleReturnRepository implements ReturnRepository
{
    protected $stockLedgerService;

    public function __construct(StockLedgerService $stockLedgerService)
    {
        $this->stockLedgerService = $stockLedgerService;
    }

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
        return DB::transaction(function () use ($data, $items) {
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

                $this->stockLedgerService->recordInbound(
                    $item['item_id'],
                    'sale_return',
                    'sale_return',
                    $return->id,
                    $item['qty'],
                    $item['unit_price']
                );
            }

            return $return;
        });
    }
}