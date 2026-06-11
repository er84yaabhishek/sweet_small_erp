@extends('layouts.admin')
@section('title', 'Frontend Settings')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-globe"></i> Frontend Settings</h2>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">General Settings</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.frontend.settings.update') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $settings['whatsapp_number'] ?? '919876543210' }}">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ $settings['phone_number'] ?? '+91 98765 43210' }}">
                </div>
                <div class="col-md-12 mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? 'support@sweetshop.com' }}">
                </div>
                <div class="col-md-12 mb-2">
                    <label>Hero Title</label>
                    <input type="text" name="hero_title" class="form-control" value="{{ $settings['hero_title'] ?? 'Manage Your Sweet Shop Easily' }}">
                </div>
                <div class="col-md-12 mb-2">
                    <label>Hero Subtitle</label>
                    <textarea name="hero_subtitle" class="form-control">{{ $settings['hero_subtitle'] ?? 'Complete ERP solution for sweet shops' }}</textarea>
                </div>
                <div class="col-md-12 mb-2">
                    <label>Shop Name</label>
                    <input type="text" name="shop_name" class="form-control" value="{{ $settings['shop_name'] ?? 'Sweet Shop ERP' }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-success text-white">Demo Requests</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>Name</th><th>Email</th><th>Phone</th><th>Shop</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($demoRequests as $demo)
                <tr>
                    <td>{{ $demo->name }}</td>
                    <td>{{ $demo->email }}</td>
                    <td>{{ $demo->phone }}</td>
                    <td>{{ $demo->shop_name ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.frontend.demo.update', $demo->id) }}">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                <option value="pending" {{ $demo->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="contacted" {{ $demo->status=='contacted'?'selected':'' }}>Contacted</option>
                                <option value="completed" {{ $demo->status=='completed'?'selected':'' }}>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '919876543210' }}?text=Hi%20{{ urlencode($demo->name) }}%2C%20We%20received%20your%20demo%20request" 
                           class="btn btn-sm btn-success" target="_blank">
                            <i class="fab fa-whatsapp"></i> Contact
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $demoRequests->links() }}
    </div>
</div>
@endsection
