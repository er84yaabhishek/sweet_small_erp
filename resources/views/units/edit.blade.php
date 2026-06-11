@extends('layouts.admin')
@section('title', 'Edit Unit')
@section('content')
<div class="card">
    <div class="card-header bg-warning">Edit Unit</div>
    <div class="card-body">
        <form action="{{ route('units.update', $unit->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Unit Name (Full)</label>
                <input type="text" name="name" value="{{ old('name', $unit->name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="short_name" class="form-label">Short Name (Abbreviation)</label>
                <input type="text" name="short_name" value="{{ old('short_name', $unit->short_name) }}" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Update Unit</button>
            <a href="{{ route('units.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
