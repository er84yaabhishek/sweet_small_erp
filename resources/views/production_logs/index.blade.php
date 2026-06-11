@extends('layouts.admin')
@section('title', 'Production Logs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-industry"></i> Production Logs</h2>
    <a href="{{ route('production-logs.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> New Production
    </a>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>Date</th><th>Product</th><th>Batches</th><th>Quantity</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->production_date }}</td>
                    <td>{{ $log->item->name }}</td>
                    <td>{{ number_format($log->batches, 2) }}</td>
                    <td>{{ number_format($log->qty_produced, 3) }}</td>
                    <td><a href="{{ route('production-logs.show', $log->id) }}" class="btn btn-sm btn-info">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</div>
@endsection
