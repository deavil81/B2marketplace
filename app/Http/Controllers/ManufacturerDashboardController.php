<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ManufacturerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'manufacturer') {
            abort(403, 'Unauthorized action.');
        }

        return view('auth.dashboard', compact('user'));
    }
}
