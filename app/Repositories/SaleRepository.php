<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    protected $stockLedgerService;

    public function __construct(StockLedgerService $stockLedgerService)
    {
        $this->stockLedgerService = $stockLedgerService;
    }

    public function getAll($perPage = 20, $filters = [])
    {
        $query = Sale::with(['customer', 'createdBy']);
        if (!empty($filters['from_date'])) {
            $query->whereDate('sale_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('sale_date', '<=', $filters['to_date']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->orderByDesc('sale_date')->paginate($perPage);
    }

    public function findById($id)
    {
        return Sale::with(['customer', 'items.item', 'payments'])->findOrFail($id);
    }

    public function create(array $data, array $items, array $payments)
    {
        return DB::transaction(function () use ($data, $items, $payments) {
            $data['invoice_no'] = $this->generateInvoiceNo();
            $data['created_by'] = auth()->id();

            $sale = Sale::create($data);

            foreach ($items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'tax_amount' => $item['tax_amount'] ?? 0,
                    'line_total' => $item['line_total'],
                ]);

                $this->stockLedgerService->recordOutbound(
                    $item['item_id'],
                    'sale',
                    'sale',
                    $sale->id,
                    $item['qty'],
                    $item['unit_price']
                );
            }

            foreach ($payments as $payment) {
                SalePayment::create([
                    'sale_id' => $sale->id,
                    'payment_mode' => $payment['payment_mode'],
                    'amount' => $payment['amount'],
                    'reference_no' => $payment['reference_no'] ?? null,
                ]);
            }

            return $sale;
        });
    }

    private function generateInvoiceNo()
    {
        $prefix = 'INV';
        $last = Sale::orderBy('id', 'desc')->first();
        $next = $last ? ((int)substr($last->invoice_no, 3)) + 1 : 1;
        return $prefix . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
