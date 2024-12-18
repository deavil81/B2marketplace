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

class HomeController extends Controller
{
    /**
     * Display the home page with suggested products and categories.
     */
    public function index() 
    { 
        $unreadNotifications = auth()->check() 
            ? Message::where('receiver_id', Auth::id())->where('read_at', null)->count() 
            : 0; 
        $messages = auth()->check() 
            ? Message::where('receiver_id', Auth::id())->get() 
            : collect(); 
        $suggestedProducts = Product::inRandomOrder()->take(8)->get(); 
        $categories = Category::all(); 
        
        return view('messages.message', compact('suggestedProducts', 'categories', 'unreadNotifications', 'messages')); 
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
        $products = Product::where('user_id', $user->id)->get();
        $categories = Category::all();
        return view('auth.profile', compact('user','products','categories'));
    }

    public function editProfile() 
    { 
        $user = Auth::user(); 
        return view('auth.profile', compact('user'));
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
        $products = Product::where('title', 'LIKE', "%$query%")->get();
        $users = User::where('name', 'LIKE', "%$query%")->get();

        return view('search.results', compact('products', 'users', 'query'));
    }
}
