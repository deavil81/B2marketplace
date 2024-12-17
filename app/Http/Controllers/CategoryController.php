<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show($id)
    {
        // Find category by ID or throw a 404 error if not found
        $category = Category::findOrFail($id);

        // Fetch products that belong to the category
        $products = Product::where('category_id', $id)->with('images')->get();

        // Return the view with the category and products data
        return view('categories.show', compact('category', 'products'));
    }
}
