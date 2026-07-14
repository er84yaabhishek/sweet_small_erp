<?php

namespace Tests\Unit\Services;

use App\Repositories\ItemRepository;
use App\Services\ItemService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_items_delegates_with_filters(): void
    {
        $repo = Mockery::mock(ItemRepository::class);
        $filters = ['item_type' => 'finished_good'];
        $repo->shouldReceive('getAll')->once()->with(10, $filters)->andReturn('list');

        $this->assertSame('list', (new ItemService($repo))->getAllItems(10, $filters));
    }

    public function test_get_item_by_id_delegates(): void
    {
        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('findById')->once()->with(3)->andReturn('item');

        $this->assertSame('item', (new ItemService($repo))->getItemById(3));
    }

    public function test_create_item_with_provided_sku_commits_and_keeps_sku(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $data = ['name' => 'Kaju Katli', 'sku' => 'KAJ0001'];
        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('create')->once()->with(Mockery::on(function ($payload) {
            return $payload['sku'] === 'KAJ0001' && $payload['name'] === 'Kaju Katli';
        }))->andReturn('created');

        $this->assertSame('created', (new ItemService($repo))->createItem($data));
    }

    public function test_create_item_stores_uploaded_image(): void
    {
        Storage::fake('public');
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $image = Mockery::mock(UploadedFile::class);
        $image->shouldReceive('isValid')->once()->andReturn(true);
        $image->shouldReceive('store')->once()->with('items', 'public')->andReturn('items/photo.jpg');

        $data = ['name' => 'Barfi', 'sku' => 'BAR0001', 'image' => $image];
        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('create')->once()->with(Mockery::on(function ($payload) {
            return $payload['image'] === 'items/photo.jpg';
        }))->andReturn('created');

        $this->assertSame('created', (new ItemService($repo))->createItem($data));
    }

    public function test_create_item_rolls_back_and_rethrows_on_failure(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('create')->once()->andThrow(new RuntimeException('db down'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('db down');

        (new ItemService($repo))->createItem(['name' => 'X', 'sku' => 'X0001']);
    }

    public function test_update_item_without_new_image_commits(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $existing = (object) ['image' => null];
        $data = ['name' => 'Renamed'];
        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('findById')->once()->with(4)->andReturn($existing);
        $repo->shouldReceive('update')->once()->with(4, $data)->andReturn('updated');

        $this->assertSame('updated', (new ItemService($repo))->updateItem(4, $data));
    }

    public function test_update_item_replaces_existing_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('items/old.jpg', 'content');

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $existing = (object) ['image' => 'items/old.jpg'];

        $image = Mockery::mock(UploadedFile::class);
        $image->shouldReceive('isValid')->once()->andReturn(true);
        $image->shouldReceive('store')->once()->with('items', 'public')->andReturn('items/new.jpg');

        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('findById')->once()->with(4)->andReturn($existing);
        $repo->shouldReceive('update')->once()->with(4, Mockery::on(function ($payload) {
            return $payload['image'] === 'items/new.jpg';
        }))->andReturn('updated');

        $this->assertSame('updated', (new ItemService($repo))->updateItem(4, ['image' => $image]));

        Storage::disk('public')->assertMissing('items/old.jpg');
    }

    public function test_delete_item_removes_image_and_commits(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('items/pic.jpg', 'content');

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $existing = (object) ['image' => 'items/pic.jpg'];
        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('findById')->once()->with(9)->andReturn($existing);
        $repo->shouldReceive('delete')->once()->with(9)->andReturn(true);

        $this->assertTrue((new ItemService($repo))->deleteItem(9));

        Storage::disk('public')->assertMissing('items/pic.jpg');
    }

    public function test_delete_item_rolls_back_on_failure(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $existing = (object) ['image' => null];
        $repo = Mockery::mock(ItemRepository::class);
        $repo->shouldReceive('findById')->once()->with(9)->andReturn($existing);
        $repo->shouldReceive('delete')->once()->andThrow(new RuntimeException('locked'));

        $this->expectException(RuntimeException::class);

        (new ItemService($repo))->deleteItem(9);
    }
}
