<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;
use App\Models\FeaturePermission;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        // Check if EXPENSES feature is enabled globally
        if (!FeaturePermission::isEnabled('EXPENSES')) {
            abort(403, 'Expenses module is not enabled in your license.');
        }

        $this->expenseService = $expenseService;
        $this->middleware('permission:view_expenses')->only(['index', 'show']);
        $this->middleware('permission:create_expense')->only(['create', 'store']);
        $this->middleware('permission:edit_expense')->only(['edit', 'update']);
        $this->middleware('permission:delete_expense')->only('destroy');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'from_date', 'to_date']);
        $expenses = $this->expenseService->getAllExpenses(20, $filters);
        $categories = $this->expenseService->getCategories();
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = $this->expenseService->getCategories();
        return view('expenses.create', compact('categories'));
    }

    public function store(ExpenseRequest $request)
    {
        $this->expenseService->createExpense($request->validated());
        return redirect()->route('expenses.index')->with('success', 'Expense recorded.');
    }

    public function edit($id)
    {
        $expense = $this->expenseService->getExpenseById($id);
        $categories = $this->expenseService->getCategories();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(ExpenseRequest $request, $id)
    {
        $this->expenseService->updateExpense($id, $request->validated());
        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy($id)
    {
        $this->expenseService->deleteExpense($id);
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}