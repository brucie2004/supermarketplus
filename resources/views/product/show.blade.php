<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/newcss.css') }}">
    @vite(['resources/css/newcss.css'])
</head>
<body>
    @include('partials.navbar') <!-- Create a navbar partial to reuse across pages -->

    <div class="container my-4">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400' }}" 
                class="img-fluid rounded" alt="{{ $product->name }}">
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
                <h1 class="mb-3">{{ $product->name }}</h1>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>
                    <span class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                        {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </div>
                
                <p class="text-muted mb-4">{{ $product->description }}</p>
                
                <!-- Add to Cart Form -->
                @if($product->stock_quantity > 0)
                   <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="quantity" class="col-form-label">Quantity:</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" role="alert">
                        This product is currently out of stock.
                    </div>
                @endif
                
                <!-- Product Metadata -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Details</h5>
                        <ul class="list-unstyled">
                            <li><strong>SKU:</strong> {{ $product->id }}</li>
                            <li><strong>Category:</strong> {{ $product->category->name }}</li>
                            <li><strong>Availability:</strong> {{ $product->stock_quantity }} in stock</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <div class="mt-5">
            <h3>You might also like</h3>
            <div class="row">
                @php
                    // Get 4 random products from the same category
                    $relatedProducts = App\Models\Product::where('category_id', $product->category_id)
                        ->where('id', '!=', $product->id)
                        ->inRandomOrder()
                        ->limit(4)
                        ->get();
                @endphp
                
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <a href="{{ route('product.show', $relatedProduct->slug) }}">
                                <img src="{{ $relatedProduct->image ? asset('storage/' . $relatedProduct->image) : 'https://via.placeholder.com/300' }}" 
                                    class="card-img-top" alt="{{ $relatedProduct->name }}" height="200" style="object-fit: cover;">
                            </a>
                            <div class="card-body">
                                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="text-decoration-none text-dark">
                                    <h5 class="card-title">{{ Str::limit($relatedProduct->name, 40) }}</h5>
                                </a>
                                <p class="card-text">${{ number_format($relatedProduct->price, 2) }}</p>
                                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>