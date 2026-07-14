<?php

namespace App\Http\Controllers;

use App\Services\PurchaseReturnService;
use App\Services\PurchaseService;
use App\Http\Requests\PurchaseReturnRequest;
use Illuminate\Http\Request;

class PurchaseReturnController extends Controller
{
    protected $returnService;
    protected $purchaseService;

    public function __construct(PurchaseReturnService $returnService, PurchaseService $purchaseService)
    {
        $this->returnService = $returnService;
        $this->purchaseService = $purchaseService;
        $this->middleware('permission:view_purchase_returns')->only(['index', 'show']);
        $this->middleware('permission:create_purchase_return')->only(['create', 'store']);
    }

    public function index()
    {
        $returns = $this->returnService->getAllReturns(20);
        return view('purchase_returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $suppliers = $this->purchaseService->getSuppliers();
        $selectedPurchase = null;
        $purchaseItems = [];
        if ($request->purchase_id) {
            $selectedPurchase = $this->purchaseService->getPurchaseById($request->purchase_id);
            $purchaseItems = $selectedPurchase->items;
        }
        return view('purchase_returns.create', compact('suppliers', 'selectedPurchase', 'purchaseItems'));
    }

    public function store(PurchaseReturnRequest $request)
    {
        $items = $this->decodeJsonArray($request->items_json, 'items_json');
        if (empty($items)) {
            return back()->withErrors('At least one item required.');
        }
        $this->returnService->createReturn($request->validated(), $items);
        return redirect()->route('purchase-returns.index')->with('success', 'Purchase return processed. Stock adjusted.');
    }

    public function show($id)
    {
        $return = $this->returnService->getReturnById($id);
        return view('purchase_returns.show', compact('return'));
    }
}
