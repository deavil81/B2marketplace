<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display the specified category along with its products.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Retrieve the specific category along with its subcategories
        $category = Category::with('subcategories')
            ->findOrFail($id);

        

        // Retrieve top-level categories with subcategories for navigation or display
        $categories = Category::whereNull('parent_id')
            ->with('subcategories')
            ->get();

        // Retrieve products related to the category
        $products = Product::where('category_id', $id)
            ->with(['images'])
            ->paginate(12);

        // Pass data to the view
        return view('categories.show', [
            'category' => $category,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    public function getSubcategories(Category $category)
    {
        if ($category->subcategories->isEmpty()) {
            return response()->json([
                'message' => 'No subcategories found for this category.',
            ], 404);
        }

        return response()->json($category->subcategories);
    }
  
}
