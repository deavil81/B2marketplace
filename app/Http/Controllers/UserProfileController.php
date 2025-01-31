<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post; // Assuming you have a Post model
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show($username)
    {
        // Find user by username
        $user = User::where('username', $username)->firstOrFail();

        // Fetch user posts
        $posts = Post::where('user_id', $user->id)->latest()->paginate(9); // Paginated posts

        // Followers and Following counts (assuming relationships exist)
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('profiles.show', compact('user', 'posts', 'followersCount', 'followingCount'));
    }
}
