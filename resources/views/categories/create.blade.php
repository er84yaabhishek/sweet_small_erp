@extends('layouts.admin')
@section('title', __('messages.create_category'))
@section('content')
<div class="card">
    <div class="card-header">{{ __('messages.create_category') }}</div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('messages.category_name') }}</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">{{ __('messages.category_type') }}</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="finished_good">{{ __('messages.finished_good') }}</option>
                    <option value="raw_material">{{ __('messages.raw_material') }}</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-large">{{ __('messages.save') }}</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-large">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection