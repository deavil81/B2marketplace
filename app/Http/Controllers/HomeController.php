<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\product;
use App\Models\Categories;
use App\Models\Message;
use App\Models\ProductReview;
use App\Models\productImage;
use App\Models\Category;
use App\Models\SearchHistory;
use App\Models\ViewedProduct;
use App\Models\ExploredCategory;
use App\Models\UserActivity;

class HomeController extends Controller
{
    /**
     * Display the home page with suggested products and categories.
     */
    public function index()
    {
        $userId = auth()->id();
    
        // Fetch suggested products based on user activity
        $suggestedProducts = $this->suggestProductsBasedOnActivity($userId);
    
        // Fetch other data for the page
        $products = Product::inRandomOrder()->take(8)->get();
        $categories = Category::all(); // Always fetch categories for "Explore by Category"
        $unreadNotifications = auth()->check()
            ? Message::where('receiver_id', $userId)->where('read_at', null)->count()
            : 0;
        $messages = auth()->check()
            ? Message::where('receiver_id', $userId)->get()
            : collect();
    
        return view('index', compact('products', 'categories', 'unreadNotifications', 'messages', 'suggestedProducts'));
    }
    public function viewProduct($productId)
    {
        $userId = auth()->id();
    
        if ($userId) {
            \Log::info('Recording product view activity', ['user_id' => $userId, 'product_id' => $productId]);
    
            try {
                UserActivity::create([
                    'user_id' => $userId,
                    'activity_type' => 'view',
                    'activity_data' => json_encode(['product_id' => $productId]),
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to record user activity', ['error' => $e->getMessage()]);
            }
        }
    
        $product = Product::find($productId);
        return view('products.show', compact('product'));
    }
        

    public function exploreCategory($categoryId)
    {
        $userId = auth()->id();
        
        if ($userId) {
            \Log::info('Recording category exploration activity', ['user_id' => $userId, 'category_id' => $categoryId]);
    
            UserActivity::create([
                'user_id' => $userId,
                'activity_type' => 'explore',
                'activity_data' => json_encode(['category_id' => $categoryId]),
            ]);
        }
    
        $category = Category::find($categoryId);
        $products = $category->products;
        return view('categories.show', compact('category', 'products'));
    }
    
    private function suggestProductsBasedOnActivity($userId)
    {
        if (!$userId) {
            \Log::info('User is a guest, returning random products');
            return Product::inRandomOrder()->take(8)->get();
        }
    
        \Log::info('User ID:', ['user_id' => $userId]);
    
        // Get recently viewed product IDs
        $viewedProductIds = UserActivity::where('user_id', $userId)
            ->where('activity_type', 'view')
            ->pluck('activity_data')
            ->map(function ($data) {
                $decoded = json_decode($data);
                return $decoded && isset($decoded->product_id) ? $decoded->product_id : null;
            })
            ->filter();
        \Log::info('Viewed Product IDs:', ['viewedProductIds' => $viewedProductIds]);
    
        // Get recently explored category IDs
        $exploredCategoryIds = UserActivity::where('user_id', $userId)
            ->where('activity_type', 'explore')
            ->pluck('activity_data')
            ->map(function ($data) {
                $decoded = json_decode($data);
                return $decoded && isset($decoded->category_id) ? $decoded->category_id : null;
            })
            ->filter();
        \Log::info('Explored Category IDs:', ['exploredCategoryIds' => $exploredCategoryIds]);
    
        // Suggest products based on viewed or explored categories
        $suggestedProducts = Product::whereIn('category_id', $exploredCategoryIds)
            ->orWhereIn('id', $viewedProductIds)
            ->distinct()
            ->take(8)
            ->get();
        \Log::info('Suggested Products:', ['suggestedProducts' => $suggestedProducts]);
    
        return $suggestedProducts;
    }
        
        
    /**
     * Display the about page.
     */
    public function about() 
    { 
        return view('about'); 
    }

    /**
     * Display the messages page.
     */
    public function showMessage() 
    { 
        $unreadNotifications = auth()->check() 
            ? Message::where('receiver_id', Auth::id())->where('read_at', null)->count() 
            : 0; 
        $messages = auth()->check() 
            ? Message::where('receiver_id', Auth::id())->get() 
            : collect(); 
        return view('messages.message', compact('unreadNotifications', 'messages')); 
    }

    /**
     * Display the user's profile page.
     */
    public function profile() 
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
    
        // Check the role of the user and redirect accordingly
        if ($user->role === 'buyer') {
            return view('auth.buyer-profile', compact('user'));
        }
    
        // For sellers, include products and categories
        $products = Product::where('user_id', $user->id)->get();
        $categories = Category::all();
    
        return view('auth.profile', compact('user', 'products', 'categories'));
    }
    
    

    public function editProfile()
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
    
        $products = Product::where('user_id', $user->id)->get();
        $categories = Category::all();
    
        return view('auth.profile', compact('user', 'products', 'categories'));
    }
    
    /**
     * Update the user's profile.
     */

    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
    
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'business_type' => 'required|string|max:255',
            'about_us' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);
    
        // Update user details
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->business_type = $validatedData['business_type'];
        $user->about_us = $validatedData['about_us'];
        $user->phone = $validatedData['phone'];
        $user->address = $validatedData['address'];
    
        // Check for password update
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }
    
        // Check for profile picture update
        if ($request->hasFile('profile_picture')) {
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $filePath;
        }
    
        $user->save();
    
        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function dashboard()
    {
        return view('auth.dashboard'); 
    }

     
        
    /**
     * Handle account deletion.
     */
    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out and delete user
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Account deleted successfully.');
    }
    /**
     * Handle search requests.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $loggedInUserId = auth()->id(); // Get the logged-in user's ID

        $products = Product::where('title', 'LIKE', "%$query%")->get();
        $users = User::where('name', 'LIKE', "%$query%")
                    ->where('id', '!=', $loggedInUserId) // Exclude the current user
                    ->get();

        return view('search.results', compact('products', 'users', 'query'));
    }

}