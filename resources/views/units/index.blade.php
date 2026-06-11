@extends('layouts.admin')
@section('title', 'Units')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-ruler"></i> Units</h2>
    <a href="{{ route('units.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> Add Unit
    </a>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr><th>ID</th><th>Full Name</th><th>Short Name</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->id }}</td>
                    <td>{{ $unit->name }}</td>
                    <td><span class="badge bg-primary">{{ $unit->short_name }}</span></td>
                    <td>
                        <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this unit?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $units->links() }}
    </div>
</div>
@endsection
