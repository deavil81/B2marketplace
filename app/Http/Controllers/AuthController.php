<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\TemporaryRegistration;
use App\Models\SellerDetail;
use App\Models\BuyerDetail;


class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register-details')->with('hideNavbar', true);
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

    protected function registered(Request $request, $user)
    {
        if ($user->role === 'buyer') {
            return redirect()->route('buyer.dashboard');
        } elseif ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect('/home');
    }

    public function showDetailsForm(Request $request)
    {
        $role = $request->input('role');
        
        // Redirect to different views based on the role
        if ($role === 'buyer') {
            return view('auth.buyer_register')->with('initialData', $request->only(['email', 'password', 'role']));
        } elseif ($role === 'seller') {
            return view('auth.register')->with('initialData', $request->only(['email', 'password', 'role']));
        }
    
        // If no valid role, redirect back with an error
        return redirect()->back()->withErrors(['role' => 'Please select a valid role']);
    }
    
    public function showDetailedRegistrationForm()
    {
        $categories = Category::with('subcategories')->get();
        $subcategories = Subcategory::all();

        return view('auth.register', compact('categories','subcategories'));
    }
    
    public function storeDetailedRegistration(Request $request)
    {
        $role = $request->input('role'); // Either 'seller' or 'buyer'
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($role === 'seller') {
            $validator->addRules([
                'about_us' => 'required|string',
                'business_type' => 'required|string',
                'catalog' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'certificates.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
                'business_name' => 'required|string|max:255',
                'product_category' => 'nillable|string|max:255',
            ]);
        } elseif ($role === 'buyer') {
            $validator->addRules([
                'business_name' => 'required|string|max:255',
                'about_us' => 'required|string',
                'industry' => 'required|string|max:255',
                'business_interest' => 'required|string|max:500',
                'sourcing_needs' => 'required|string|max:500',
                'annual_budget' => 'nullable|string|max:255', // Fixed field name
                'company_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
            ]);
        }
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $existingUser = User::where('email', $request->input('email'))->first();
    
        if ($existingUser) {
            if (Hash::check($request->input('password'), $existingUser->password)) {
                $existingUser->update([
                    'name' => $request->input('name'),
                    'role' => $role,
                ]);
    
                if ($request->hasFile('profile_picture')) {
                    $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $existingUser->update(['profile_picture' => $profilePicturePath]);
                }
    
                $user = $existingUser;
            } else {
                return redirect()->back()->withErrors(['password' => 'The password does not match our records.']);
            }
        } else {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => $role,
            ]);
    
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->update(['profile_picture' => $profilePicturePath]);
            }
        }
    
        // Store Seller Details
        if ($role === 'seller') {
            $user->update([
                'about_us' => $request->input('about_us'),
                'business_type' => $request->input('business_type'),
                'catalog' => $request->hasFile('catalog') ? $request->file('catalog')->store('catalogs', 'public') : null,
                'certificates' => $request->hasFile('certificates') 
                    ? json_encode(array_map(fn($file) => $file->store('certificates', 'public'), $request->file('certificates')))
                    : null,
            ]);
    
            // Store seller-specific details in the `sellers` table
            $user->seller()->updateOrCreate(['user_id' => $user->id], [
                'business_name' => $request->input('business_name'),
                'product_category' => $request->input('product_category'),
            ]);
        }
    
        // Store Buyer Details
        elseif ($role === 'buyer') {
            $user->buyer()->updateOrCreate(['user_id' => $user->id], [
                'business_name' => $request->input('business_name'),
                'industry' => $request->input('industry'),
                'business_interest' => $request->input('business_interest'),
                'sourcing_needs' => $request->input('sourcing_needs'),
                'annual_budget' => $request->input('annual_budget'), // Fixed field name
                'company_document' => $request->hasFile('company_document') 
                    ? $request->file('company_document')->store('company_documents', 'public') 
                    : null,
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);
        }
    
        // Log the user in
        Auth::login($user);
    
        $message = $existingUser ? 'Registration updated successfully.' : 'Registration completed successfully.';
    
        // Redirect to the home page with a success message
        return redirect('/')->with('success', $message);
    }
                  
    /**
     * Handle single file upload.
     *
     * @param Request $request
     * @param string $fieldName
     * @param string $directory
     * @return string|null
     */
    private function uploadFile(Request $request, string $fieldName, string $directory)
    {
        if ($request->hasFile($fieldName)) {
            return $request->file($fieldName)->store($directory, 'public');
        }
        return null;
    }
    
    /**
     * Handle multiple file uploads.
     *
     * @param Request $request
     * @param string $fieldName
     * @param string $directory
     * @return array
     */
    private function uploadMultipleFiles(Request $request, string $fieldName, string $directory)
    {
        $paths = [];
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                $paths[] = $file->store($directory, 'public');
            }
        }
        return $paths;
    }
}    