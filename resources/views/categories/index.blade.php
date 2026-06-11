@extends('layouts.admin')
@section('title', __('messages.categories'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ __('messages.categories') }}</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> {{ __('messages.create_category') }}
    </a>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>{{ __('messages.category_name') }}</th>
                <th>{{ __('messages.category_type') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->type == 'finished_good' ? __('messages.finished_good') : __('messages.raw_material') }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> {{ __('messages.edit') }}</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> {{ __('messages.delete') }}</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $categories->links() }}
@endsection