<?php

namespace App\Services;

use App\Models\StockLedger;

class StockLedgerService
{
    public function recordInbound($itemId, $transactionType, $referenceType, $referenceId, $quantity, $rate = null)
    {
        return $this->record(
            $itemId,
            $transactionType,
            $referenceType,
            $referenceId,
            $quantity,
            0,
            $rate
        );
    }

    public function recordOutbound($itemId, $transactionType, $referenceType, $referenceId, $quantity, $rate = null)
    {
        return $this->record(
            $itemId,
            $transactionType,
            $referenceType,
            $referenceId,
            0,
            $quantity,
            $rate
        );
    }

    private function record($itemId, $transactionType, $referenceType, $referenceId, $quantityIn, $quantityOut, $rate)
    {
        return StockLedger::create([
            'item_id' => $itemId,
            'txn_type' => $transactionType,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'qty_in' => $quantityIn,
            'qty_out' => $quantityOut,
            'rate' => $rate,
            'created_by' => auth()->id(),
        ]);
    }
}
