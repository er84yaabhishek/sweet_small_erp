<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use App\Services\CategoryService;
use App\Services\UnitService;
use App\Services\TaxRateService; // You may need to create simple TaxRateService or repository
use App\Http\Requests\ItemRequest;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemService;
    protected $categoryService;
    protected $unitService;
    protected $taxRateService;

    public function __construct(
        ItemService $itemService,
        CategoryService $categoryService,
        UnitService $unitService
        // TaxRateService $taxRateService optionally
    ) {
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
        $this->unitService = $unitService;
        $this->middleware('permission:view_items')->only(['index', 'show']);
        $this->middleware('permission:create_item')->only(['create', 'store']);
        $this->middleware('permission:edit_item')->only(['edit', 'update']);
        $this->middleware('permission:delete_item')->only('destroy');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['item_type', 'is_sellable', 'search']);
        $items = $this->itemService->getAllItems(20, $filters);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories(100); // get all for dropdown
        $units = $this->unitService->getAllUnits(100);
        // $taxRates = TaxRate::all(); // if needed
        return view('items.create', compact('categories', 'units'));
    }

    public function store(ItemRequest $request)
    {
        $this->itemService->createItem($request->validated());
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $item = $this->itemService->getItemById($id);
        $categories = $this->categoryService->getAllCategories(100);
        $units = $this->unitService->getAllUnits(100);
        return view('items.edit', compact('item', 'categories', 'units'));
    }

    public function update(ItemRequest $request, $id)
    {
        $this->itemService->updateItem($id, $request->validated());
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $this->itemService->deleteItem($id);
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}