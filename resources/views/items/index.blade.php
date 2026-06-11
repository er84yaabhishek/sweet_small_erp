@extends('layouts.admin')
@section('title', 'Items')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-box"></i> Items</h2>
    <a href="{{ route('items.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> Add Item
    </a>
</div>

<form method="GET" class="row g-3 mb-3">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Search by name/SKU/barcode" value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="item_type" class="form-select">
            <option value="">All Types</option>
            <option value="raw_material" {{ request('item_type')=='raw_material' ? 'selected' : '' }}>Raw Material</option>
            <option value="finished_good" {{ request('item_type')=='finished_good' ? 'selected' : '' }}>Finished Good</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="is_sellable" class="form-select">
            <option value="">All</option>
            <option value="1" {{ request('is_sellable')=='1' ? 'selected' : '' }}>Sellable</option>
            <option value="0" {{ request('is_sellable')=='0' ? 'selected' : '' }}>Not Sellable</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th><th>Name/SKU</th><th>Category</th><th>Unit</th><th>Type</th>
                        <th>Sellable</th><th>Purchasable</th><th>Sale Price</th><th>Stock</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                            @else
                                <i class="fas fa-box fa-2x text-muted"></i>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item->name }}</strong><br>
                            <small class="text-muted">SKU: {{ $item->sku ?? 'N/A' }}</small><br>
                            <small class="text-muted">Barcode: {{ $item->barcode ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td>{{ $item->unit->short_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $item->item_type == 'finished_good' ? 'bg-success' : 'bg-info' }}">
                                {{ $item->item_type == 'finished_good' ? 'Finished' : 'Raw' }}
                            </span>
                        </td>
                        <td>{!! $item->is_sellable ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}</td>
                        <td>{!! $item->is_purchasable ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}</td>
                        <td>₹{{ number_format($item->sale_price, 2) }}</td>
                        <td>
                            <span class="{{ $item->currentStock() < $item->reorder_level ? 'text-danger fw-bold' : '' }}">
                                {{ number_format($item->currentStock(), 2) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('reports.stock-ledger', $item->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-history"></i> Ledger
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $items->appends(request()->query())->links() }}
    </div>
</div>
@endsection