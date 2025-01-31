<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterDetailsController extends Controller
{
    public function store(Request $request)
    {
        // Handle the form data and save to the database
        // Example: $request->file('profile_pic')->store('public/profile_pics');
        
        return redirect()->route('home')->with('success', 'Profile details saved successfully.');
    }
}
