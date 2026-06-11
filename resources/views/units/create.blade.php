@extends('layouts.admin')
@section('title', 'Create Unit')
@section('content')
<div class="card">
    <div class="card-header bg-success text-white">Create New Unit</div>
    <div class="card-body">
        <form action="{{ route('units.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Unit Name (Full)</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       placeholder="e.g., Kilogram" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="short_name" class="form-label">Short Name (Abbreviation)</label>
                <input type="text" name="short_name" class="form-control @error('short_name') is-invalid @enderror" 
                       placeholder="e.g., KG" required>
                @error('short_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-success btn-large">Save Unit</button>
            <a href="{{ route('units.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
