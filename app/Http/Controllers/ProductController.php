<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Show the user's products and allow adding/editing.
     */
    public function index()
    {
        $user = Auth::user();
        $products = Product::where('user_id', $user->id)->with('images')->get();
        $categories = Category::all(); // Fetch categories for the add/edit product forms
        return view('profile', compact('user', 'products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories')); // Adjust to your view file
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
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        return view('products.edit', compact('product', 'categories'));
    }
    
    public function show($id)
    {
        $product = Product::with(['images', 'reviews.user'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
    
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);
    
        ProductReview::create([
            'product_id' => $id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);
    
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail_image' => 'nullable|exists:product_images,id',
        ]);
    
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        // Store new images if any are uploaded
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        // Handle the image upload and thumbnail setting
        if ($request->has('thumbnail_image')) {
            // Reset all other images' is_thumbnail attribute
            $product->images()->update(['is_thumbnail' => false]);

            // Set the selected image as thumbnail
            $thumbnail = $product->images()->find($request->input('thumbnail_image'));
            if ($thumbnail) {
                $thumbnail->update(['is_thumbnail' => true]);
                $product->thumbnail_image_id = $thumbnail->id;
            }
        } elseif ($product->images()->exists()) {
            // Default to the first uploaded image if no thumbnail is selected
            $firstImage = $product->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_thumbnail' => true]);
                $product->thumbnail_image_id = $firstImage->id;
            }
        }

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
