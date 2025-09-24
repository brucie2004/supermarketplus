<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | SuperMarketPlus</title>
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
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </nav>

        <h1 class="mb-4">Shopping Cart</h1>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(count($cartItems) > 0)
            <div class="row">
                <!-- Cart Items Column (Left side on large screens) -->
                <div class="col-lg-8 col-md-7 mb-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Cart Items ({{ count($cartItems) }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach($cartItems as $item)
                                @if(isset($item['product']))
                                    <div class="row mb-4 border-bottom pb-3">
                                        <div class="col-3 col-md-2">
                                            <a href="{{ route('product.show', $item['product']->slug) }}">
                                                <img src="{{ $item['product']->image ? asset('storage/' . $item['product']->image) : 'https://via.placeholder.com/100' }}" 
                                                    alt="{{ $item['product']->name }}" class="img-fluid rounded">
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-7">
                                            <a href="{{ route('product.show', $item['product']->slug)}}" class="text-decoration-none text-dark">
                                                <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                            </a>
                                            <p class="text-muted small mb-1">{{ Str::limit($item['product']->description, 50) }}</p>
                                            <p class="text-success mb-0">${{ number_format($item['product']->price, 2) }} each</p>
                                        </div>
                                        <div class="col-3 col-md-3">
                                            <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="mb-2">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                           min="1" max="{{ $item['product']->stock_quantity }}" 
                                                           class="form-control form-control-sm">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Update</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Order Summary Column (Right side on large screens) -->
                <div class="col-lg-4 col-md-5">
                    <div class="card sticky-top" style="top: 100px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>$5.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (10%):</span>
                                <span>${{ number_format($total * 0.1, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong>${{ number_format($total + 5 + ($total * 0.1), 2) }}</strong>
                            </div>
                            
                            <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-credit-card"></i> Proceed to Checkout
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 4rem;"></i>
                <h3 class="mt-3">Your cart is empty</h3>
                <p class="text-muted">Start shopping to add items to your cart</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">Continue Shopping</a>
            </div>
        @endif
    </div>
    
    @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>