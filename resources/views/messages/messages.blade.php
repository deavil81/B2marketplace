@extends('layouts.navlayout')

@section('title', 'Messaging')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar: List of Conversations -->
        <div class="col-md-4 border-end">
            <h5>Conversations</h5>
            <ul class="list-group">
                @forelse ($conversations as $conversation)
                    <li class="list-group-item {{ isset($activeUser) && $conversation->id === $activeUser->id ? 'active' : '' }}">
                        <a href="{{ route('messages.index', ['user_id' => $conversation->id]) }}" class="text-decoration-none text-dark">
                            <strong>{{ $conversation->name }}</strong><br>
                            <small class="text-muted">
                                {{ $conversation->lastMessage ?? 'No messages yet' }}
                            </small>
                        </a>
                    </li>
                @empty
                    <li class="list-group-item">No conversations found.</li>
                @endforelse
            </ul>
        </div>

        <!-- Main Chat Section -->
        <div class="col-md-8">
            @if(isset($activeUser) && isset($messages))
                <h5>Chat with {{ $activeUser->name }}</h5>
                <div class="border p-3 chat-window" style="height: 60vh; overflow-y: scroll;">
                    @forelse ($messages as $message)
                        <div class="mb-3 {{ $message->sender_id === auth()->id() ? 'text-end' : 'text-start' }}">
                            <p class="mb-1 {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-2 rounded">
                                {{ $message->content }}
                            </p>
                            <small class="text-muted">
                                {{ $message->created_at->format('d M Y, h:i A') }}
                            </small>
                        </div>
                    @empty
                        <p>No messages in this conversation yet.</p>
                    @endforelse
                </div>

                <!-- Message Input -->
                <form action="{{ route('messages.store') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeUser->id }}">
                    <div class="input-group">
                        <textarea name="content" class="form-control" rows="1" placeholder="Type a message..." required></textarea>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            @else
                <p class="text-center">Select a conversation to start chatting.</p>
            @endif
        </div>


        <!-- Start a New Conversation -->
        <div class="mt-4">
            <h5>Start a Conversation</h5>
            <form action="{{ route('messages.startConversation') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <select name="receiver_id" class="form-control" required>
                        <option value="" disabled selected>Select a user...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <textarea name="content" class="form-control" rows="1" placeholder="Type a message..." required></textarea>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
