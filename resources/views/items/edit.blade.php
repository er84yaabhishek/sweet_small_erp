@extends('layouts.admin')
@section('title', 'Edit Item')
@section('content')
<div class="card">
    <div class="card-header bg-warning">Edit Item</div>
    <div class="card-body">
        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('items._form', [
                'submitLabel' => 'Update Item',
                'submitClass' => 'btn-warning',
            ])
        </form>
    </div>
</div>
@endsection