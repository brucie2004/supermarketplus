<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    @include('partials.navbar')
    
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Confirmation</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0"><i class="bi bi-check-circle-fill"></i> Order Confirmed</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            <h2 class="mt-3">Thank You for Your Order!</h2>
                            <p class="lead">Your order has been placed successfully.</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Order Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Order #:</strong> {{ $order->id }}</p>
                                        <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                                        <p class="mb-1"><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Shipping Information</h6>
                                    </div>
                                    <div class="card-body">
    <p class="mb-1"><strong>Shipping Address:</strong></p>
    <p class="mb-0">{!! nl2br(e($order->shipping_address)) !!}</p>
</div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3">Order Items</h5>
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
                                            <td>{{ $item->product->name }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <th>${{ number_format($order->total_amount - 5 - ($order->total_amount * 0.1), 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Shipping:</th>
                                        <th>$5.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Tax:</th>
                                        <th>${{ number_format($order->total_amount * 0.1, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>${{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary me-md-2">Continue Shopping</a>
                            <a href="{{ route('profile.orders') }}" class="btn btn-outline-secondary">View Order History</a>
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