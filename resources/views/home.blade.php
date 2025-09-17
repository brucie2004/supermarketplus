<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">${{ $product->price }}</p>
                            <a href="#" class="btn btn-outline-primary">Add to Cart</a>
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