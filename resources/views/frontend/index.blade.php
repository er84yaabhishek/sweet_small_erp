@extends('frontend.layout')
@section('title', 'Home')

@section('content')
<section class="py-5 text-center bg-warning bg-opacity-10">
    <div class="container">
        <h1 class="display-4">{{ $settings['hero_title'] ?? 'Manage Your Sweet Shop Easily' }}</h1>
        <p class="lead">{{ $settings['hero_subtitle'] ?? 'Complete ERP solution for sweet shops, bakeries, and food businesses' }}</p>
        <a href="#demo" class="btn btn-warning btn-lg px-4">Request Demo</a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">Login</a>
    </div>
</section>

<section id="features" class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <i class="fas fa-cash-register fa-3x text-warning"></i>
                    <h5 class="mt-2">POS Billing</h5>
                    <p>Fast billing with GST</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <i class="fas fa-boxes fa-3x text-warning"></i>
                    <h5 class="mt-2">Inventory</h5>
                    <p>Real-time stock</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <i class="fas fa-industry fa-3x text-warning"></i>
                    <h5 class="mt-2">Production</h5>
                    <p>Recipe & batches</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <i class="fas fa-chart-line fa-3x text-warning"></i>
                    <h5 class="mt-2">Reports</h5>
                    <p>Sales & profit</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="demo" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">Request Free Demo</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('demo.request') }}">
                            @csrf
                            <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            <input type="tel" name="phone" class="form-control mb-2" placeholder="Phone" required>
                            <input type="text" name="shop_name" class="form-control mb-2" placeholder="Shop Name">
                            <textarea name="message" class="form-control mb-2" rows="3" placeholder="Your message..."></textarea>
                            <button type="submit" class="btn btn-success w-100">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
