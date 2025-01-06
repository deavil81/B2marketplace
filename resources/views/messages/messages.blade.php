@extends('layouts.navlayout')

@section('title', 'Messaging')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Conversations List Sidebar -->
        <div class="col-md-4 border-end">
            <h5>Conversations</h5>
            <ul class="list-group">
                @foreach ($conversations as $conversation)
                    @php
                        $chatPartner = $conversation->user1_id === auth()->id() ? $conversation->user2 : $conversation->user1;
                    @endphp
                    <li class="list-group-item {{ $activeUser && $activeUser->id === $chatPartner->id ? 'active' : '' }}">
                        <a href="{{ route('messages.index', ['user_id' => $chatPartner->id]) }}" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="{{ asset($chatPartner->profile_picture ?? 'default-avatar.png') }}" alt="{{ $chatPartner->name }}" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                                </div>
                                <div>
                                    <strong>{{ $chatPartner->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $conversation->messages->last()->content ?? 'No messages yet.' }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Chat Panel -->
        <div class="col-md-8">
            @if ($activeUser)
                <div class="mb-3 chat-header d-flex align-items-center">
                    <div class="avatar me-3">
                        <img src="{{ asset($activeUser->profile_picture ?? 'default-avatar.png') }}" alt="{{ $activeUser->name }}" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                    </div>
                    <h5 class="mb-0">{{ $activeUser->name }}</h5>
                </div>

                <!-- Messages Display -->
                <div id="chat-messages" class="p-3 mb-3 border chat-messages" style="height: 400px; overflow-y: scroll;">
                    @if ($messages && $messages->count())
                        @foreach ($messages as $message)
                            <div class="mb-2 {{ $message->sender_id === auth()->id() ? 'text-end' : '' }}">
                                <strong>{{ $message->sender_id === auth()->id() ? 'You' : $activeUser->name }}:</strong>
                                <p>{{ $message->content }}</p>
                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No messages yet. Start the conversation!</p>
                    @endif
                </div>

                <!-- Message Input -->
                <form id="message-form">
                    @csrf
                    <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $activeUser->id }}">
                    <div class="input-group">
                        <input type="text" name="content" id="message-content" class="form-control" placeholder="Type a message..." required>
                        <button type="button" id="send-message" class="btn btn-primary">Send</button>
                    </div>
                </form>
                @error('content')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            @else
                <p class="text-muted">Select a conversation to start chatting.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/laravel-echo"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Initialize Pusher
    Pusher.logToConsole = true;

    const echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        forceTLS: true
    });

    @if ($activeUser && isset($conversation))
    echo.private('conversation.{{ $conversation->id }}')
        .listen('MessageSent', (event) => {
            const authId = {{ auth()->id() }};
            const isAuthUser = event.message.sender_id === authId;
            const messageContainer = document.getElementById('chat-messages');
            messageContainer.innerHTML += `
                <div class="mb-2 ${isAuthUser ? 'text-end' : ''}">
                    <strong>${isAuthUser ? 'You' : '{{ $activeUser->name }}'}:</strong>
                    <p>${event.message.content}</p>
                    <small class="text-muted">${new Date(event.message.created_at).toLocaleTimeString()}</small>
                </div>
            `;
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });
    @endif

    // Handle Sending Messages
    document.getElementById('send-message').addEventListener('click', function () {
        const receiverId = document.getElementById('receiver_id').value;
        const content = document.getElementById('message-content').value;

        fetch('{{ route('messages.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                content: content,
            }),
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.status === 'Message Sent!') {
                document.getElementById('message-content').value = ''; // Clear the input field
            } else {
                alert('Failed to send the message.');
            }
        })
        .catch((error) => console.error('Error sending message:', error));
    });
</script>
@endpush
