<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
    
        if ($user->role === 'buyer') {
            return redirect()->route('buyer.dashboard');
        }
    
        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard'); // Define seller.dashboard similarly
        }
    
        return redirect('/'); // Default fallback
    }
    
}
