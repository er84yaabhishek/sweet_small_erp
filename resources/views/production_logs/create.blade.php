@extends('layouts.admin')
@section('title', 'Record Production')
@section('content')
<div class="card">
    <div class="card-header bg-success text-white">Record New Production</div>
    <div class="card-body">
        <form action="{{ route('production-logs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="recipe_id" class="form-label">Select Recipe</label>
                <select name="recipe_id" class="form-control" required>
                    <option value="">Choose Recipe</option>
                    @foreach($recipes as $recipe)
                        <option value="{{ $recipe->id }}">{{ $recipe->item->name }} (Batch: {{ $recipe->batch_qty }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="batches" class="form-label">Number of Batches</label>
                <input type="number" step="0.01" name="batches" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="production_date" class="form-label">Production Date</label>
                <input type="date" name="production_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Notes</label>
                <textarea name="note" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Record Production</button>
            <a href="{{ route('production-logs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
