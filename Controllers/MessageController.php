<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
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
    
        // Fetch all conversations of the authenticated user
        $conversations = Conversation::forUser($userId)
            ->with(['user1', 'user2', 'messages.sender'])
            ->get();
    
        // Extract users involved in the conversations
        $users = $conversations->map(function ($conversation) use ($userId) {
            return $conversation->user1_id === $userId ? $conversation->user2 : $conversation->user1;
        });
    
        // Determine the active user based on the query parameter
        $activeUserId = $request->query('user_id');
        $activeUser = $activeUserId ? User::find($activeUserId) : null;
    
        if ($activeUserId && !$activeUser) {
            return redirect()->route('messages.index')->withErrors('Invalid user selected.');
        }
    
        // Fetch messages for the active conversation
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
            })
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            : collect();
    
        return view('messages.messages', compact('users', 'activeUser', 'messages'));
    }
    
    
    /**
     * Store and send a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::firstOrCreate([
            'user1_id' => min(auth()->id(), $request->receiver_id),
            'user2_id' => max(auth()->id(), $request->receiver_id),
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => htmlspecialchars($request->content, ENT_QUOTES, 'UTF-8'), // XSS protection
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => $message], 201);
    }

    /**
     * Start a conversation with a user.
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'rfq_id' => 'nullable|exists:rfqs,id', // Include RFQ ID if available
            'content' => 'nullable|string|max:1000',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->receiver_id;

        // Prevent users from messaging themselves
        if ($senderId === $receiverId) {
            return redirect()->route('messages.index')->withErrors('You cannot start a conversation with yourself.');
        }

        $user1Id = min($senderId, $receiverId);
        $user2Id = max($senderId, $receiverId);

        $conversation = Conversation::firstOrCreate([
            'user1_id' => $user1Id,
            'user2_id' => $user2Id,
        ]);

        // Send a default message if the RFQ ID is provided
        if ($request->filled('rfq_id')) {
            $conversation->messages()->create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'content' => "Hello! Your proposal for RFQ #{$request->rfq_id} has been accepted. Let's discuss further.",
            ]);
        }

        return redirect()->route('messages.index', ['user_id' => $receiverId]);
    }

}
