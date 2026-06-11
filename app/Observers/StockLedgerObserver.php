<?php

namespace App\Observers;

use App\Models\StockLedger;
use App\Models\Item;

class StockLedgerObserver
{
    public function created(StockLedger $stockLedger)
    {
        $item = Item::find($stockLedger->item_id);
        if ($item) {
            $item->updateCurrentStock();
        }
    }

    public function updated(StockLedger $stockLedger)
    {
        $item = Item::find($stockLedger->item_id);
        if ($item) {
            $item->updateCurrentStock();
        }
    }

    public function deleted(StockLedger $stockLedger)
    {
        $item = Item::find($stockLedger->item_id);
        if ($item) {
            $item->updateCurrentStock();
        }
    }
}
