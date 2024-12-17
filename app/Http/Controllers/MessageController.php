<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('receiver_id', Auth::id())->orWhere('sender_id', Auth::id())->get();
        $users = User::all();

        // Count unread notifications
        $unreadNotifications = Message::where('receiver_id', Auth::id())->where('read_at', null)->count();

        // Correct view path
        return view('messages.Message', compact('messages', 'users', 'unreadNotifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->input('receiver_id'),
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
