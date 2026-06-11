@extends('layouts.admin')
@section('title', 'Recipes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-book"></i> Recipes (BOM)</h2>
    <a href="{{ route('recipes.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> Create Recipe
    </a>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>ID</th><th>Finished Good</th><th>Batch Qty</th><th>Unit</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($recipes as $recipe)
                <tr>
                    <td>{{ $recipe->id }}</td>
                    <td><strong>{{ $recipe->item->name }}</strong></td>
                    <td>{{ number_format($recipe->batch_qty, 2) }}</td>
                    <td>{{ $recipe->batchUnit->short_name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $recipes->links() }}
    </div>
</div>
@endsection
