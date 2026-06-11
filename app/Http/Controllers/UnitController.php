<?php

namespace App\Http\Controllers;

use App\Services\UnitService;
use App\Http\Requests\UnitRequest;

class UnitController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
        $this->middleware('permission:view_units')->only(['index', 'show']);
        $this->middleware('permission:create_unit')->only(['create', 'store']);
        $this->middleware('permission:edit_unit')->only(['edit', 'update']);
        $this->middleware('permission:delete_unit')->only('destroy');
    }

    public function index()
    {
        $units = $this->unitService->getAllUnits(20);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(UnitRequest $request)
    {
        $this->unitService->createUnit($request->validated());
        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function edit($id)
    {
        $unit = $this->unitService->getUnitById($id);
        return view('units.edit', compact('unit'));
    }

    public function update(UnitRequest $request, $id)
    {
        $this->unitService->updateUnit($id, $request->validated());
        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy($id)
    {
        $this->unitService->deleteUnit($id);
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
