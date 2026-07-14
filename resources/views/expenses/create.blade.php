@extends('layouts.admin')
@section('title', 'Add Expense')
@section('content')
<div class="card">
    <div class="card-header bg-success text-white">Record New Expense</div>
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            @include('expenses._form', [
                'expense' => null,
                'submitLabel' => 'Save Expense',
                'submitClass' => 'btn-success btn-large',
            ])
        </form>
    </div>
</div>
@endsection