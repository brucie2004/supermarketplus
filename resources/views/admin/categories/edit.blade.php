@extends('admin.layout')

@section('title', 'Edit Category')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <h4 class="card-title">Edit Category</h4>
        
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">Select Parent Category (Optional)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id) == $parent->id) ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave empty to make this a main category.</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if($category->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="100" class="img-thumbnail">
                                <br>
                                <small>Current image</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Optional category description">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Category Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Subcategories:</strong> {{ $category->children->count() }}
                            </div>
                            <div class="col-md-4">
                                <strong>Products:</strong> {{ $category->products->count() }}
                            </div>
                            <div class="col-md-4">
                                <strong>Created:</strong> {{ $category->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection