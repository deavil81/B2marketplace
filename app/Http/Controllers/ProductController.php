<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Show the user's products and allow adding/editing.
     */
    public function index()
    {
        $user = Auth::user();
        $products = Product::where('user_id', $user->id)->with('images')->get();
        $categories = Category::all(); // Fetch categories
        return view('profile', compact('user', 'products', 'categories'));
    }
    
    /**
     * Show the form for creating a new product.
     */
    public function create() {
        $categories = Category::with('subcategories')->get();
        return view('products.create', compact('categories'));
    }
    
    
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store method called', $request->all()); // Debugging log
    
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        \Log::info('Validation passed'); // Debugging log
    
        // Create the product
        $product = Product::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
        ]);
    
        \Log::info('Product created', $product->toArray()); // Debugging log
    
        // Handle thumbnail image
        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('product_images', 'public');
    
            // Save the thumbnail as a ProductImage
            $thumbnailImage = $product->images()->create([
                'image_path' => $thumbnailPath,
            ]);
    
            // Set the thumbnail for the product
            $product->thumbnail_image_id = $thumbnailImage->id;
            $product->save();
        }
    
        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }
    
        // Fallback: Set the first uploaded image as the thumbnail if no thumbnail is explicitly provided
        if (!$product->thumbnail_image_id && $product->images()->exists()) {
            $firstImage = $product->images()->first();
            $product->thumbnail_image_id = $firstImage->id;
            $product->save();
        }
    
        return redirect()->route('profile.index')->with('success', 'Product added successfully!');
    }
        
    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        $subcategories = \App\Models\Subcategory::where('category_id', $product->category_id)->get();
    
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
    
        return view('products.edit', compact('product', 'categories', 'subcategories'));
    }
        
    public function show($id)
    {
        $product = Product::with(['reviews.user'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $id)
                                  ->limit(4)
                                  ->get();
    
        // Log the product and related products
        \Log::info('Product Data:', $product->toArray());
        \Log::info('Related Products:', $relatedProducts->toArray());
    
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    public function showSuggestions()
    {
        $suggestedProducts = Product::where('category_id', $someCategoryId)->get();
    
        // Log the retrieved products
        \Log::info('Suggested Products:', $suggestedProducts->toArray());
    
        return view('products.suggestions', compact('suggestedProducts'));
    }

    public function list(Request $request)
    {
        // Fetch categories and subcategories for filtering
        $categories = Category::with('subcategories')->get();
        $subcategories = \App\Models\Subcategory::all();

        // Validate request parameters
        $request->validate([
            'category' => 'nullable|exists:categories,id',
            'subcategory' => 'nullable|exists:subcategories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|in:price_low_high,price_high_low,new_arrivals,bestselling',
        ]);

        // Build the product query
        $query = Product::query()->with('images');

        // Apply filters
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        if ($request->subcategory) {
            $query->where('subcategory_id', $request->subcategory);
        }
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high_low':
                    $query->orderBy('price', 'desc');
                    break;
                case 'new_arrivals':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'bestselling':
                    $query->orderBy('sales_count', 'desc'); // Assuming sales_count exists
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }

        // Paginate results
        $products = $query->paginate(20);

        // Pass data to the view
        return view('products.list', compact('products', 'categories', 'subcategories'));
    }


    public function storeReview(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit a review.');
        }
    
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);
    
        try {
            ProductReview::create([
                'product_id' => $id,
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]);
            \Log::info('Review successfully stored.', ['data' => $validated]);
        } catch (\Exception $e) {
            \Log::error('Failed to store review.', ['error' => $e->getMessage()]);
            return redirect()->route('products.show', $id)->with('error', 'Failed to submit review.');
        }
    
        return redirect()->route('products.show', $id)->with('success', 'Review submitted successfully!');
    }
                
    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail_image' => 'nullable|exists:product_images,id',
        ]);
    
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
        ]);
    
        // Handle image uploads and thumbnail setting (existing logic here)
    
        $product->save();
    
        return redirect()->route('profile.index')->with('success', 'Product updated successfully.');
    }
        
        
    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('profile.index')->with('success', 'Product deleted successfully.');
    }
}
