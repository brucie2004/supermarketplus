<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">SuperMarketPlus</a>
            <!-- ... your nav links ... -->
        </div>
    </nav>

    <div class="container my-4">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1>Category: {{ $category->name }}</h1>

        <!-- Display Sub-Categories -->
        @if($childCategories->count() > 0)
            <div class="mb-4">
                <h3>Sub-Categories</h3>
                <div class="row">
                    @foreach($childCategories as $subCategory)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $subCategory->name }}</h5>
                                    <a href="{{ route('category.show', $subCategory->slug) }}" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr>
        @endif

        <!-- Display Products -->
        <h2>Products</h2>
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}" height="200" style="object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </h5>
                            <p class="card-text">${{ number_format($product->price, 2) }}</p>
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- This block runs if there are NO products -->
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        No products found in this category yet.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>