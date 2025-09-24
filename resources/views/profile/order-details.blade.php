<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }} Details | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/newcss.css') }}">
    @vite(['resources/css/newcss.css'])
</head>
<body>
    @include('partials.navbar')
    
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">My Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.orders') }}">Order History</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Order #{{ $order->id }}</h4>
                        <span class="badge bg-light text-dark">
                            {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Order Status Timeline -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>Order Status</h5>
                                <div class="progress" style="height: 30px;">
                                    @php
                                        $statuses = ['pending', 'confirmed', 'shipped', 'completed'];
                                        $currentIndex = array_search($order->status, $statuses);
                                        $percentage = ($currentIndex + 1) / count($statuses) * 100;
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" 
                                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    @foreach($statuses as $status)
                                        <small class="{{ $status == $order->status ? 'fw-bold text-success' : 'text-muted' }}">
                                            {{ ucfirst($status) }}
                                        </small>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Order Details -->
                            <div class="col-md-6">
                                <h5>Order Information</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \\a\\t g:i A') }}</p>
                                        <p><strong>Order Status:</strong> 
                                            <span class="badge 
                                                @if($order->status == 'completed') bg-success
                                                @elseif($order->status == 'pending') bg-warning
                                                @elseif($order->status == 'cancelled') bg-danger
                                                @elseif($order->status == 'shipped') bg-info
                                                @else bg-secondary @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Shipping Information -->
                            <div class="col-md-6">
                                <h5>Shipping Information</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <p>{!! nl2br(e($order->shipping_address)) !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Order Items</h5>
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
                                                            alt="{{ $item->product->name }}" class="img-thumbnail">
                                                            <div>
                                                                <strong>{{ $item->product->name }}</strong>
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
                                                <td colspan="3" class="text-end"><strong>Tax:</strong></td>
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
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <a href="{{ route('profile.orders') }}" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-arrow-left"></i> Back to Orders
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="bi bi-bag"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>