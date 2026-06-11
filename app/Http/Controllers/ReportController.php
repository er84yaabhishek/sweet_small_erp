<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Item;
use App\Models\StockLedger;
use App\Models\FeaturePermission;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_reports');
    }

    public function dashboard()
    {
        // Today's sales
        $todaySales = Sale::whereDate('sale_date', today())->sum('total_amount');
        $todaySalesCount = Sale::whereDate('sale_date', today())->count();

        // Low stock items (below reorder_level)
        $lowStockItems = Item::where('is_active', true)
            ->where('reorder_level', '>', 0)
            ->get()
            ->filter(function ($item) {
                return $item->currentStock() < $item->reorder_level;
            });

        // Recent activities: last 5 sales and purchases
        $recentSales = Sale::with('customer')->orderByDesc('created_at')->limit(5)->get();
        $recentPurchases = Purchase::with('supplier')->orderByDesc('created_at')->limit(5)->get();

        // Monthly sales chart data (last 12 months)
        $monthlySales = Sale::select(
                DB::raw('YEAR(sale_date) as year'),
                DB::raw('MONTH(sale_date) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse();

        return view('reports.dashboard', compact(
            'todaySales', 'todaySalesCount', 'lowStockItems',
            'recentSales', 'recentPurchases', 'monthlySales'
        ));
    }

    public function stockReport(Request $request)
    {
        $items = Item::with(['category', 'unit']);
        
        if ($request->category_id) {
            $items->where('category_id', $request->category_id);
        }
        if ($request->item_type) {
            $items->where('item_type', $request->item_type);
        }
        if ($request->search) {
            $items->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        $items = $items->paginate(20);
        
        // Calculate current stock for each item
        foreach ($items as $item) {
            $item->current_stock = $item->currentStock();
        }

        $categories = \App\Models\Category::all();
        return view('reports.stock', compact('items', 'categories'));
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $sales = Sale::with('customer')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('sale_date')
            ->paginate(20);

        $totalSales = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
        
        $totalTax = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('tax_amount');

        // Top selling items
        $topItems = DB::table('sale_items')
            ->join('items', 'sale_items.item_id', '=', 'items.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', 'completed')
            ->select('items.name', DB::raw('SUM(sale_items.qty) as total_qty'), DB::raw('SUM(sale_items.line_total) as total_amount'))
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return view('reports.sales', compact('sales', 'totalSales', 'totalTax', 'topItems', 'startDate', 'endDate'));
    }

    public function purchaseReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $purchases = Purchase::with('supplier')
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->orderBy('purchase_date')
            ->paginate(20);

        $totalPurchases = Purchase::whereBetween('purchase_date', [$startDate, $endDate])->sum('total_amount');
        $totalPaid = Purchase::whereBetween('purchase_date', [$startDate, $endDate])->sum('paid_amount');
        $totalDue = $totalPurchases - $totalPaid;

        return view('reports.purchases', compact('purchases', 'totalPurchases', 'totalPaid', 'totalDue', 'startDate', 'endDate'));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        // Revenue
        $totalRevenue = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        // Cost of Goods Sold (simple: purchase cost of items sold)
        // This is approximate; for accurate COGS, you'd need average cost method.
        $cogs = DB::table('sale_items')
            ->join('items', 'sale_items.item_id', '=', 'items.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', 'completed')
            ->select(DB::raw('SUM(sale_items.qty * items.purchase_price) as total_cogs'))
            ->value('total_cogs') ?? 0;

        // Expenses (if feature enabled)
        $expensesEnabled = FeaturePermission::isEnabled('EXPENSES');
        $totalExpenses = 0;
        if ($expensesEnabled) {
            $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        }

        $grossProfit = $totalRevenue - $cogs;
        $netProfit = $grossProfit - $totalExpenses;

        return view('reports.profit_loss', compact(
            'totalRevenue', 'cogs', 'grossProfit', 'totalExpenses', 'netProfit', 'startDate', 'endDate', 'expensesEnabled'
        ));
    }

    public function stockLedger($itemId, Request $request)
    {
        $item = Item::findOrFail($itemId);
        $startDate = $request->start_date ?? now()->subDays(30)->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $entries = StockLedger::with('createdBy')
            ->where('item_id', $itemId)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        // Calculate running balance
        $balance = 0;
        foreach ($entries as $entry) {
            $balance += $entry->qty_in - $entry->qty_out;
            $entry->balance = $balance;
        }

        return view('reports.stock_ledger', compact('item', 'entries', 'startDate', 'endDate'));
    }
}