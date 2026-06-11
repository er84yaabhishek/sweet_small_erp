<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use App\Http\Requests\SupplierRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
        $this->middleware('permission:view_suppliers')->only(['index', 'show']);
        $this->middleware('permission:create_supplier')->only(['create', 'store']);
        $this->middleware('permission:edit_supplier')->only(['edit', 'update']);
        $this->middleware('permission:delete_supplier')->only('destroy');
    }

    public function index(Request $request)
    {
        $suppliers = $this->supplierService->getAllSuppliers(20, $request->search);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(SupplierRequest $request)
    {
        $this->supplierService->createSupplier($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, $id)
    {
        $this->supplierService->updateSupplier($id, $request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $this->supplierService->deleteSupplier($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}