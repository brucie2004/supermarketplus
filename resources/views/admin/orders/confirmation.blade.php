@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-check-circle-fill"></i> Payment Successful</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h2 class="mt-3">Thank You for Your Order!</h2>
                        <p class="lead">Your payment was processed successfully.</p>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5>Order Details</h5>
                            <p><strong>Order #:</strong> {{ $order->id }}</p>
                            <p><strong>Total Paid:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-success">Paid</span></p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('home') }}" class="btn btn-primary me-md-2">Continue Shopping</a>
                        <a href="{{ route('profile.orders') }}" class="btn btn-outline-secondary">View Order History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection