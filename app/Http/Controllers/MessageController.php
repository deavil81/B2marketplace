<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use App\Events\MessageSent;

class MessageController extends Controller
{
    /**
     * Display the messaging interface.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Fetch conversations
        $conversations = Conversation::forUser($userId)
            ->with(['user1', 'user2'])
            ->get();

        // Get the active user if provided
        $activeUser = $request->query('user_id') 
            ? User::find($request->query('user_id')) 
            : null;

        if ($request->query('user_id') && !$activeUser) {
            return redirect()->route('messages.index')->withErrors('Invalid user selected.');
        }

        // Fetch messages for the active user
        $messages = $activeUser
            ? Message::where('conversation_id', function ($query) use ($userId, $activeUser) {
                $query->select('id')
                    ->from('conversations')
                    ->where(function ($subQuery) use ($userId, $activeUser) {
                        $subQuery->where('user1_id', $userId)
                            ->where('user2_id', $activeUser->id);
                    })
                    ->orWhere(function ($subQuery) use ($userId, $activeUser) {
                        $subQuery->where('user1_id', $activeUser->id)
                            ->where('user2_id', $userId);
                    });
            })->orderBy('created_at', 'asc')->get()
            : null;

        // All users except the current authenticated user
        $users = User::where('id', '!=', $userId)->get();

        return view('messages.messages', compact('conversations', 'activeUser', 'messages', 'users'));
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
    
        $user1_id = min(auth()->id(), $request->receiver_id);
        $user2_id = max(auth()->id(), $request->receiver_id);
    
        // Fetch or create the conversation
        $conversation = Conversation::firstOrCreate(
            [
                'user1_id' => $user1_id,
                'user2_id' => $user2_id,
            ]
        );
    
        // Create a new message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);
    
        // Broadcast the message to other participants
        broadcast(new MessageSent($message))->toOthers();
    
        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'time' => $message->created_at->diffForHumans(),
            ],
        ]);
    }
       
    /**
     * Start a conversation and send a message.
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'nullable|string|max:1000',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        // Ensure user1_id is always the smaller ID
        $user1Id = min($senderId, $receiverId);
        $user2Id = max($senderId, $receiverId);

        // Log user IDs
        Log::info('User1 ID: ' . $user1Id);
        Log::info('User2 ID: ' . $user2Id);

        // Create or find the conversation
        $conversation = Conversation::firstOrCreate(
            [
                'user1_id' => $user1Id,
                'user2_id' => $user2Id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // If content is provided, create the initial message
        if ($request->filled('content')) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'content' => $request->content,
            ]);
        }

        // Redirect to the messaging page with the active user
        return redirect()->route('messages.index', ['user_id' => $receiverId]);
    }
}
