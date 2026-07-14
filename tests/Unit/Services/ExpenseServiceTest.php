<?php

namespace Tests\Unit\Services;

use App\Repositories\ExpenseRepository;
use App\Services\ExpenseService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class ExpenseServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_expenses_delegates_with_filters(): void
    {
        $repo = Mockery::mock(ExpenseRepository::class);
        $filters = ['category' => 'Rent'];
        $repo->shouldReceive('getAll')->once()->with(15, $filters)->andReturn('list');

        $this->assertSame('list', (new ExpenseService($repo))->getAllExpenses(15, $filters));
    }

    public function test_get_expense_by_id_delegates(): void
    {
        $repo = Mockery::mock(ExpenseRepository::class);
        $repo->shouldReceive('findById')->once()->with(2)->andReturn('expense');

        $this->assertSame('expense', (new ExpenseService($repo))->getExpenseById(2));
    }

    public function test_create_expense_sets_creator(): void
    {
        Auth::shouldReceive('id')->andReturn(99);

        $repo = Mockery::mock(ExpenseRepository::class);
        $repo->shouldReceive('create')->once()->with(Mockery::on(function ($data) {
            return $data['created_by'] === 99 && $data['amount'] === 500;
        }))->andReturn('created');

        $this->assertSame('created', (new ExpenseService($repo))->createExpense(['amount' => 500]));
    }

    public function test_update_expense_delegates(): void
    {
        $data = ['amount' => 700];
        $repo = Mockery::mock(ExpenseRepository::class);
        $repo->shouldReceive('update')->once()->with(3, $data)->andReturn('updated');

        $this->assertSame('updated', (new ExpenseService($repo))->updateExpense(3, $data));
    }

    public function test_delete_expense_delegates(): void
    {
        $repo = Mockery::mock(ExpenseRepository::class);
        $repo->shouldReceive('delete')->once()->with(3)->andReturn(true);

        $this->assertTrue((new ExpenseService($repo))->deleteExpense(3));
    }

    public function test_get_categories_returns_expected_list(): void
    {
        $repo = Mockery::mock(ExpenseRepository::class);
        $categories = (new ExpenseService($repo))->getCategories();

        $this->assertContains('Rent', $categories);
        $this->assertContains('Other', $categories);
        $this->assertCount(9, $categories);
    }
}
