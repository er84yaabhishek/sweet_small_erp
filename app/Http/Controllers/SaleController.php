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

    public function store(SaleRequest $request)
    {
        $items = $this->decodeJsonArray($request->input('items_json'), 'items_json');
        $payments = $this->decodeJsonArray($request->input('payments_json'), 'payments_json');

        if (empty($items)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'No items selected'], 422);
            }
            return back()->withErrors('At least one item is required.');
        }

        if (empty($payments)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'No payment added'], 422);
            }
            return back()->withErrors('At least one payment is required.');
        }

        $sale = $this->saleService->createSale($request->validated(), $items, $payments);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'sale_id' => $sale->id]);
        }

        return redirect()->route('sales.show', $sale->id)->with('success', 'Bill created successfully.');
    }

    public function show($id)
    {
        $sale = $this->saleService->getSaleById($id);
        return view('sales.show', compact('sale'));
    }

    public function destroy($id)
    {
        abort(405, 'Sales cannot be deleted. Use return or cancellation instead.');
    }

    public function thermalReceipt($id)
{
    $sale = $this->saleService->getSaleById($id);
    $settings = \App\Models\Setting::all()->pluck('value', 'key');
    return view('sales.thermal_receipt', compact('sale', 'settings'));
}
}
