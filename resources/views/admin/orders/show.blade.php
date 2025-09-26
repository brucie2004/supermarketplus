@extends('admin.layout')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Order #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-4">
            <!-- Order Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}</p>
                        <p><strong>Order Status:</strong> 
                            <span class="badge 
                                @if($order->status == 'completed') bg-success
                                @elseif($order->status == 'pending') bg-warning
                                @elseif($order->status == 'cancelled') bg-danger
                                @elseif($order->status == 'shipped') bg-info
                                @elseif($order->status == 'delivered') bg-primary
                                @else bg-secondary @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                        
                        <!-- Update Status Form -->
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <select class="form-select" name="status">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        <p><strong>Member Since:</strong> {{ $order->user->created_at->format('F j, Y') }}</p>
                        <p><strong>Total Orders:</strong> {{ $order->user->orders->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Shipping Address -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        {!! $shippingAddress !!}
                    </div>
                </div>
            </div>
            
            <!-- Billing Address -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Billing Address</h5>
                    </div>
                    <div class="card-body">
                        @if($billingAddress)
                            {!! $billingAddress !!}
                        @else
                            <p class="text-muted">Same as shipping address</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/50' }}" 
                                                 alt="{{ $item->product->name }}" width="50" height="50" 
                                                 style="object-fit: cover;" class="rounded me-3">
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">SKU: {{ $item->product->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td><strong>${{ number_format($order->total_amount - 5 - ($order->total_amount * 0.1), 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                <td><strong>$5.00</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tax (10%):</strong></td>
                                <td><strong>${{ number_format($order->total_amount * 0.1, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 text-center">
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')" 
                  class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Delete Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection