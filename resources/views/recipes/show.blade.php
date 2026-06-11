@extends('layouts.admin')
@section('title', 'Recipe Details')
@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h3>Recipe for {{ $recipe->item->name }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Batch Quantity:</strong> {{ number_format($recipe->batch_qty, 2) }} {{ $recipe->batchUnit->short_name }}</p>
        @if($recipe->notes)<p><strong>Notes:</strong> {{ $recipe->notes }}</p>@endif
        
        <h5>Ingredients</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>Raw Material</th><th>Quantity</th><th>Unit</th></tr>
            </thead>
            <tbody>
                @foreach($recipe->ingredients as $ing)
                <tr>
                    <td>{{ $ing->ingredientItem->name }}</td>
                    <td>{{ number_format($ing->qty_required, 3) }}</td>
                    <td>{{ $ing->unit->short_name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Back</a>
        <a href="{{ route('production-logs.create') }}?recipe_id={{ $recipe->id }}" class="btn btn-success">Produce Now</a>
    </div>
</div>
@endsection
