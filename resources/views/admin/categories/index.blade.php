@extends('admin.layout')

@section('title', 'Manage Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Category Management</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add New Category
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Subcategories</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" width="50" height="50" 
                                         style="object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-tag text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($category->parent)
                                    <span class="badge bg-info">{{ $category->parent->name }}</span>
                                @else
                                    <span class="badge bg-success">Main Category</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $category->children->count() }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Category Tree View -->
            <div class="mt-5">
                <h4>Category Hierarchy</h4>
                <div class="category-tree">
                    @foreach($parentCategories as $parentCategory)
                        <div class="category-item mb-2">
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                @if($parentCategory->image)
                                    <img src="{{ asset('storage/' . $parentCategory->image) }}" 
                                         alt="{{ $parentCategory->name }}" width="30" height="30" 
                                         style="object-fit: cover;" class="rounded me-2">
                                @endif
                                <strong>{{ $parentCategory->name }}</strong>
                                <span class="badge bg-primary ms-2">{{ $parentCategory->products->count() }} products</span>
                            </div>
                            
                            @if($parentCategory->children->count() > 0)
                                <div class="subcategories ms-4 mt-2">
                                    @foreach($parentCategory->children as $childCategory)
                                        <div class="d-flex align-items-center p-2 bg-white border rounded mb-1">
                                            @if($childCategory->image)
                                                <img src="{{ asset('storage/' . $childCategory->image) }}" 
                                                     alt="{{ $childCategory->name }}" width="25" height="25" 
                                                     style="object-fit: cover;" class="rounded me-2">
                                            @endif
                                            <span>{{ $childCategory->name }}</span>
                                            <span class="badge bg-secondary ms-2">{{ $childCategory->products->count() }} products</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags" style="font-size: 4rem;"></i>
                <h3 class="mt-3">No categories found</h3>
                <p class="text-muted">Get started by creating your first category.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-2">Create First Category</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .category-tree {
        max-height: 400px;
        overflow-y: auto;
    }
    .category-item {
        border-left: 3px solid #0d6efd;
    }
    .subcategories {
        border-left: 2px solid #6c757d;
    }
</style>
@endpush