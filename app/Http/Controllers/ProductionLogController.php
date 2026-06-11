<?php

namespace App\Http\Controllers;

use App\Services\ProductionLogService;
use App\Services\RecipeService;
use App\Http\Requests\ProductionLogRequest;

class ProductionLogController extends Controller
{
    protected $logService;
    protected $recipeService;

    public function __construct(ProductionLogService $logService, RecipeService $recipeService)
    {
        $this->logService = $logService;
        $this->recipeService = $recipeService;
        $this->middleware('permission:view_production_logs')->only(['index', 'show']);
        $this->middleware('permission:create_production_log')->only(['create', 'store']);
    }

    public function index()
    {
        $logs = $this->logService->getAllLogs(20);
        return view('production_logs.index', compact('logs'));
    }

    public function create()
    {
        $recipes = $this->recipeService->getAllRecipes(100);
        return view('production_logs.create', compact('recipes'));
    }

    public function store(ProductionLogRequest $request)
    {
        $this->logService->createLog($request->validated());
        return redirect()->route('production-logs.index')->with('success', 'Production logged. Stock updated.');
    }

    public function show($id)
    {
        $log = $this->logService->getLogById($id);
        return view('production_logs.show', compact('log'));
    }
}
