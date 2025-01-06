<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Validate login inputs
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'No account found with this email.'])->withInput();
        }
        
        // Log for debugging
        Log::info('Attempting to login with credentials: ', $credentials);
        
        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }
        
        return redirect()->route('home')->with('success', 'You are logged in!');
    }
    
    //logout function
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }


    public function register(Request $request)
    {
        // Validate registration inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:buyer,seller',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Create new user
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'name' => 'Temporary Name', // Temporary placeholder
        ]);
    
        // Log the hashed password
        Log::info('User registered with hashed password:', ['password' => $user->password]);
    
        // Log the user in
        Auth::login($user);
    
        // Redirect to detailed registration form with initial data
        return redirect()->route('register.details');
    }
    
    public function storeDetailedRegistration(Request $request)
    {
        // Validate additional registration inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'about_us' => 'required|string',
            'business_type' => 'required|string',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the user with additional information
        $user = Auth::user();
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');

        $user->update([
            'name' => $request->name,
            'about_us' => $request->about_us,
            'business_type' => $request->business_type,
            'phone' => $request->phone,
            'address' => $request->address,
            'profile_picture' => $profilePicturePath,
        ]);

        // Log the updated user data for verification
        Log::info('User Registration Updated:', $user->toArray());

        // Redirect to home page after successful registration
        return redirect()->route('home')->with('success', 'Registration details saved successfully.');
    }
}
