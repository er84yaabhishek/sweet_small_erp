<?php

namespace App\Http\Controllers;

use App\Services\SaleService;
use App\Http\Requests\SaleRequest;
use Illuminate\Http\Request;
use App\Models\FeaturePermission;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
        $this->middleware('permission:view_sales')->only(['index', 'show']);
        $this->middleware('permission:create_sale')->only(['create', 'store']);
        $this->middleware('permission:edit_sale')->only(['edit', 'update']);
        $this->middleware('permission:delete_sale')->only('destroy');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'status']);
        $sales = $this->saleService->getAllSales(20, $filters);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $items = $this->saleService->getSellableItems();
        $customers = $this->saleService->getCustomers();
        $multiPaymentEnabled = FeaturePermission::isEnabled('MULTI_PAYMENT');
        return view('sales.create', compact('items', 'customers', 'multiPaymentEnabled'));
    }

    // public function store(SaleRequest $request)
    // {
    //     $items = json_decode($request->items_json, true);
    //     $payments = json_decode($request->payments_json, true);

    //     if (empty($items)) {
    //         return back()->withErrors('At least one item required.');
    //     }
    //     if (empty($payments)) {
    //         return back()->withErrors('At least one payment required.');
    //     }

    //     $sale = $this->saleService->createSale($request->validated(), $items, $payments);
    //     return redirect()->route('sales.show', $sale->id)->with('success', 'Bill created successfully.');
    // }

    public function store(SaleRequest $request)
{
    $items = $request->input('items', []);
    $payments = $request->input('payments', []);
    
    if (empty($items)) {
        return response()->json(['message' => 'No items'], 422);
    }
    if (empty($payments)) {
        return response()->json(['message' => 'No payments'], 422);
    }
    
    $sale = $this->saleService->createSale($request->validated(), $items, $payments);
    
    if ($request->wantsJson()) {
        return response()->json(['success' => true, 'sale_id' => $sale->id]);
    }
    
    return redirect()->route('sales.show', $sale->id)->with('success', 'Bill created successfully.');

    public function show($id)
    {
        $sale = $this->saleService->getSaleById($id);
        return view('sales.show', compact('sale'));
    }

    public function destroy($id)
    {
        // Disallow deletion - use returns or adjustment
        abort(405, 'Sales cannot be deleted. Use return or cancellation.');
    }
}