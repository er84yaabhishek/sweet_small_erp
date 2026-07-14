@extends('layouts.admin')
@section('title', 'Create Item')
@section('content')
<div class="card">
    <div class="card-header bg-success text-white">Create New Item</div>
    <div class="card-body">
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('items._form', [
                'item' => null,
                'submitLabel' => 'Save Item',
                'submitClass' => 'btn-success btn-large',
            ])
        </form>
    </div>
</div>
@endsection