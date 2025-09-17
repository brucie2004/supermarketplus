<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{



    public function show($slug)
    {
        

        $category= Category::where('slug', $slug)->first();
        if (!$category) {
            abort(404); 
        }
        $childCategories = Category::where('parent_id', $category->id)->get();        
        $products = $category->products;
        foreach ($childCategories as $child)
        {

            $products = $products->merge($child->products);
            
        }

        //$products = $products->paginate(12);
        return view('category.show', compact('category', 'products','childCategories'));
    }


}
