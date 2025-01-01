<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;


class MessageController extends Controller
{
    /**
     * Display the messaging interface.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Fetch distinct conversations
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->selectRaw('IF(sender_id = ?, receiver_id, sender_id) as user_id', [$userId])
            ->distinct()
            ->with(['sender', 'receiver'])
            ->get();
        
        // Get the active conversation (if any)
        $activeUser = $request->query('user_id') 
            ? User::find($request->query('user_id')) 
            : null;
        
        // Fetch messages if a user is selected
        $messages = $activeUser
            ? Message::where(function ($query) use ($userId, $activeUser) {
                  $query->where('sender_id', $userId)->where('receiver_id', $activeUser->id);
              })->orWhere(function ($query) use ($userId, $activeUser) {
                  $query->where('sender_id', $activeUser->id)->where('receiver_id', $userId);
              })->orderBy('created_at', 'asc')->get()
            : null;
    
        // Get all users except the current authenticated user
        $users = User::where('id', '!=', $userId)->get();
        
        return view('messages.index', compact('conversations', 'activeUser', 'messages', 'users'));
    }
    
                

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        // Check if a conversation already exists
        $conversation = Conversation::firstOrCreate([
            'user_one' => min($senderId, $receiverId),
            'user_two' => max($senderId, $receiverId),
        ]);

        // Store the message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $request->content,
        ]);

        return redirect()->route('messages.index', ['user_id' => $receiverId]);
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);
    
        $senderId = auth()->id();
        $receiverId = $request->receiver_id;
    
        // Create or find the conversation between the sender and receiver
        $conversation = Conversation::firstOrCreate([
            'user_one' => min($senderId, $receiverId),
            'user_two' => max($senderId, $receiverId),
        ]);
    
        // Store the initial message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $request->content,
        ]);
    
        return redirect()->route('messages.index', ['user_id' => $receiverId]);
    }
    

}
