@extends('layouts.admin')
@section('title', 'Edit Expense')
@section('content')
<div class="card">
    <div class="card-header bg-warning">Edit Expense</div>
    <div class="card-body">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
            @csrf @method('PUT')
            @include('expenses._form', [
                'submitLabel' => 'Update Expense',
                'submitClass' => 'btn-warning',
            ])
        </form>
    </div>
</div>
@endsection