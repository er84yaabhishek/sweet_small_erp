<?php

namespace App\Http\Controllers;

use App\Services\SaleReturnService;
use App\Services\SaleService;
use App\Http\Requests\SaleReturnRequest;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    protected $returnService;
    protected $saleService;

    public function __construct(SaleReturnService $returnService, SaleService $saleService)
    {
        $this->returnService = $returnService;
        $this->saleService = $saleService;
        $this->middleware('permission:view_sale_returns')->only(['index', 'show']);
        $this->middleware('permission:create_sale_return')->only(['create', 'store']);
    }

    public function index()
    {
        $returns = $this->returnService->getAllReturns(20);
        return view('sale_returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $sale = null;
        $saleItems = [];
        if ($request->invoice_no) {
            $sale = $this->saleService->getSaleByInvoiceNo($request->invoice_no);
            $saleItems = $sale->items; // for selecting items to return
        }
        return view('sale_returns.create', compact('sale', 'saleItems'));
    }

    public function store(SaleReturnRequest $request)
    {
        $items = $this->decodeJsonArray($request, 'items_json');
        if (empty($items)) {
            return back()->withErrors('At least one item to return.');
        }
        $this->returnService->createReturn($request->validated(), $items);
        return redirect()->route('sale-returns.index')->with('success', 'Sale return processed. Stock updated.');
    }

    public function show($id)
    {
        $return = $this->returnService->getReturnById($id);
        return view('sale_returns.show', compact('return'));
    }
}