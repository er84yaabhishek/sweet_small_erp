<?php

namespace Tests\Unit\Services;

use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_categories_delegates_to_repository(): void
    {
        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('getAll')->once()->with(20)->andReturn('list');

        $this->assertSame('list', (new CategoryService($repo))->getAllCategories());
    }

    public function test_get_category_by_id_delegates_to_repository(): void
    {
        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('findById')->once()->with(5)->andReturn('cat');

        $this->assertSame('cat', (new CategoryService($repo))->getCategoryById(5));
    }

    public function test_create_category_commits_transaction_and_returns_model(): void
    {
        $data = ['name' => 'Sweets', 'type' => 'finished_good'];
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('create')->once()->with($data)->andReturn('created');

        $this->assertSame('created', (new CategoryService($repo))->createCategory($data));
    }

    public function test_create_category_rolls_back_and_rethrows_on_failure(): void
    {
        $data = ['name' => 'Sweets'];
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('create')->once()->andThrow(new RuntimeException('boom'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('boom');

        (new CategoryService($repo))->createCategory($data);
    }

    public function test_update_category_commits_transaction(): void
    {
        $data = ['name' => 'Updated'];
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('update')->once()->with(2, $data)->andReturn('updated');

        $this->assertSame('updated', (new CategoryService($repo))->updateCategory(2, $data));
    }

    public function test_update_category_rolls_back_on_failure(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('update')->once()->andThrow(new RuntimeException('fail'));

        $this->expectException(RuntimeException::class);

        (new CategoryService($repo))->updateCategory(2, []);
    }

    public function test_delete_category_commits_transaction(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('delete')->once()->with(8)->andReturn(true);

        $this->assertTrue((new CategoryService($repo))->deleteCategory(8));
    }

    public function test_delete_category_rolls_back_on_failure(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $repo = Mockery::mock(CategoryRepository::class);
        $repo->shouldReceive('delete')->once()->andThrow(new RuntimeException('nope'));

        $this->expectException(RuntimeException::class);

        (new CategoryService($repo))->deleteCategory(8);
    }
}
