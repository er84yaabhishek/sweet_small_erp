<?php

namespace App\Http\Controllers;

use App\Services\PurchaseService;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
        $this->middleware('permission:view_purchases')->only(['index', 'show']);
        $this->middleware('permission:create_purchase')->only(['create', 'store']);
        $this->middleware('permission:edit_purchase')->only(['edit', 'update']);
        $this->middleware('permission:delete_purchase')->only('destroy');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['supplier_id', 'from_date', 'to_date']);
        $purchases = $this->purchaseService->getAllPurchases(20, $filters);
        $suppliers = $this->purchaseService->getSuppliers();
        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = $this->purchaseService->getSuppliers();
        $items = $this->purchaseService->getPurchasableItems();
        return view('purchases.create', compact('suppliers', 'items'));
    }

    public function store(PurchaseRequest $request)
    {
        // Items come as array from dynamic form
        $items = json_decode($request->items_json, true); // Assume we send JSON from frontend
        if (!$items || count($items) == 0) {
            return back()->withErrors('At least one item required.');
        }
        $this->purchaseService->createPurchase($request->validated(), $items);
        return redirect()->route('purchases.index')->with('success', 'Purchase bill created and stock updated.');
    }

    public function show($id)
    {
        $purchase = $this->purchaseService->getPurchaseById($id);
        return view('purchases.show', compact('purchase'));
    }

    public function edit($id)
    {
        // Editing purchase is complex due to stock ledger; we may disallow or implement reversal.
        abort(405, 'Editing purchase not allowed. Use return or adjustment.');
    }

    public function destroy($id)
    {
        $this->purchaseService->deletePurchase($id);
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted (stock entries remain for audit).');
    }
}