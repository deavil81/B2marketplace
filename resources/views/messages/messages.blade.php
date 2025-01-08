@extends('layouts.navlayout')

@section('title', 'Messaging')

@section('content')
<div class="container mt-4 d-flex">

    <!-- Sidebar for Users List -->
    <div class="col-md-3 border-end">
        <h5 class="p-2">Chats</h5>
        <ul class="list-group" style="max-height: 500px; overflow-y: auto;">
            @forelse ($users as $user)
                <a href="{{ route('messages.index', ['user_id' => $user->id]) }}" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $activeUser && $activeUser->id === $user->id ? 'active' : '' }}">
                    <div>
                        <strong>{{ $user->name }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ optional($user->latestMessage)->content ?? 'No messages yet.' }}
                        </small>
                    </div>
                </a>
            @empty
                <p class="text-muted p-2">No conversations yet. Start a new one!</p>
            @endforelse
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <strong>{{ $activeUser ? $activeUser->name : 'Select a chat' }}</strong>
            </div>
            <div class="card-body chat-area" style="height: 400px; overflow-y: scroll; background-color: #f8f9fa;">
                @if ($messages && $messages->count())
                    @foreach ($messages as $message)
                        <div class="mb-2 {{ $message->sender_id === auth()->id() ? 'text-end' : '' }}">
                            <strong>{{ $message->sender_id === auth()->id() ? 'You' : $activeUser->name }}:</strong>
                            <p class="d-inline-block {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }} p-2 rounded">
                                {{ $message->content }}
                            </p>
                            <small class="text-muted d-block">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No messages yet. Start the conversation!</p>
                @endif
            </div>
            <div class="card-footer">
                <form id="message-form" class="d-flex">
                    @csrf
                    <input type="text" id="message-input" placeholder="Type a message..." class="form-control me-2" required>
                    <input type="hidden" id="receiver_id" value="{{ $activeUser ? $activeUser->id : '' }}">
                    <button type="button" id="send-message" class="btn btn-primary">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const sendButton = document.getElementById('send-message');
    const messageInput = document.getElementById('message-input');
    const messageContainer = document.querySelector('.chat-area');

    sendButton.onclick = function () {
        const content = messageInput.value.trim();
        const receiverId = document.getElementById('receiver_id').value;

        if (content === '') {
            alert('Please enter a message before sending.');
            return;
        }

        axios.post('{{ route('messages.store') }}', {
            receiver_id: receiverId,
            content: content,
        })
        .then((response) => {
            messageContainer.innerHTML += `
                <div class="mb-2 text-end">
                    <strong>You:</strong>
                    <p class="d-inline-block bg-primary text-white p-2 rounded">${response.data.message.content}</p>
                    <small class="text-muted d-block">${response.data.message.time}</small>
                </div>
            `;
            messageInput.value = '';
            messageContainer.scrollTop = messageContainer.scrollHeight;
        })
        .catch((error) => {
            console.error('Error sending message:', error);
        });
    };

    // Scroll to the latest message on page load
    messageContainer.scrollTop = messageContainer.scrollHeight;
</script>
@endsection
