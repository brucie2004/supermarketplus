<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/newcss.css') }}">
    @vite(['resources/css/newcss.css'])
</head>
<body>
    @include('partials.navbar')
    

    <!-- A simple navigation bar -->
    
    <div class="container my-4">
        <h1>Welcome to SuperMarketPlus</h1>
        <p class="lead">Your one-stop shop for everything.</p>

        <hr>

        <h2>Categories</h2>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <a href="{{ route('category.show', $category->slug) }}" class="btn btn-primary">Browse</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <hr>

        <h2>Featured Products</h2>
        <div class="row">
            @foreach($featuredProducts as $product)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                                class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                <h5 class="card-title">{{ $product->name }}</h5>
                            </a>
                            <p class="card-text">${{ number_format($product->price, 2) }}</p>
                            <div class="mt-auto">
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

   @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>