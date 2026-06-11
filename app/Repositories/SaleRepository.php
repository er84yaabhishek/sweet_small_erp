<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use App\Models\FeaturePermission;

class SaleRepository
{
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

    public function findByInvoiceNo($invoiceNo)
    {
        return Sale::where('invoice_no', $invoiceNo)->firstOrFail();
    }

    public function create(array $data, array $items, array $payments)
    {
        DB::beginTransaction();
        try {
            // Generate invoice number
            $data['invoice_no'] = $this->generateInvoiceNo();
            $data['created_by'] = auth()->id();

            $sale = Sale::create($data);

            // Sale items & stock deduction
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

                // Stock ledger OUT
                StockLedger::create([
                    'item_id' => $item['item_id'],
                    'txn_type' => 'sale',
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'qty_in' => 0,
                    'qty_out' => $item['qty'],
                    'rate' => $item['unit_price'],
                    'created_by' => auth()->id(),
                ]);
            }

            // Payments
            foreach ($payments as $payment) {
                SalePayment::create([
                    'sale_id' => $sale->id,
                    'payment_mode' => $payment['payment_mode'],
                    'amount' => $payment['amount'],
                    'reference_no' => $payment['reference_no'] ?? null,
                ]);
            }

            DB::commit();
            return $sale;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStatus($id, $status)
    {
        $sale = $this->findById($id);
        $sale->status = $status;
        $sale->save();
        return $sale;
    }

    private function generateInvoiceNo()
    {
        $prefix = 'INV';
        $last = Sale::orderBy('id', 'desc')->first();
        $next = $last ? ((int)substr($last->invoice_no, 3)) + 1 : 1;
        return $prefix . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}