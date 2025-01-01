<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class UserController extends Controller
{
    /**
     * Search for users.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        // Validate the search query
        $request->validate([
            'query' => 'required|string|max:255',
        ]);
    
        // Paginate products and users for better UX
        $products = Product::where('title', 'LIKE', "%$query%")->paginate(10);
        $users = User::where('name', 'LIKE', "%$query%")->paginate(10);
    
        // Pass both products and users to the view
        return view('search.results', compact('products', 'users', 'query'));
    }
    


    /**
     * Display user profile along with their products.
     */
    public function show($id)
    {
        $user = User::findOrFail($id); // Fetch user by ID
        $products = $user->products()->paginate(10); // Fetch user's products (ensure the relationship exists)

        return view('users.profile', compact('user', 'products'));
    }

}