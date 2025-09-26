<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
{
    // Eager load all necessary relationships
    $categories = Category::with(['parent', 'children', 'products'])->orderBy('name')->get();
    
    // Separate parent categories and build tree structure for display
    $parentCategories = $categories->whereNull('parent_id');
    
    return view('admin.categories.index', compact('categories', 'parentCategories'));
}

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->where('id', '!=', $category->id)
                                   ->orderBy('name')
                                   ->get();
        
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        'parent_id' => 'nullable|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Prevent a category from being its own parent
    if ($request->parent_id == $category->id) {
        return redirect()->back()->with('error', 'A category cannot be its own parent.');
    }

    // Prevent circular references (category becoming a parent of its own parent)
    if ($request->parent_id) {
        $potentialParent = Category::find($request->parent_id);
        
        // Check if the potential parent is already a child of this category
        if ($potentialParent->parent_id == $category->id) {
            return redirect()->back()->with('error', 'Cannot set a child category as parent.');
        }
        
        // Check for deeper circular references
        $currentParentId = $potentialParent->parent_id;
        while ($currentParentId) {
            if ($currentParentId == $category->id) {
                return redirect()->back()->with('error', 'Circular reference detected. This would create an infinite loop.');
            }
            $currentParent = Category::find($currentParentId);
            $currentParentId = $currentParent ? $currentParent->parent_id : null;
        }
    }

    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->description = $request->description;
    $category->parent_id = $request->parent_id;

    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $imagePath = $request->file('image')->store('categories', 'public');
        $category->image = $imagePath;
    }

    $category->save();

    return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
}

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category that has products. Please reassign products first.');
        }

        // Check if category has subcategories
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category that has subcategories. Please delete or reassign subcategories first.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}