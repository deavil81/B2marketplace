@extends('layouts.navlayout')

@section('title', 'Messaging')

@section('content')
<div class="container mt-4 d-flex">

    <!-- Sidebar for Users List -->
    <div class="col-md-3 border-end">
        <h5 class="p-2">Chats</h5>
        <ul class="list-group">
            @forelse ($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $activeUser && $activeUser->id === $user->id ? 'active' : '' }}">
                    <a href="{{ route('messages.index', ['user_id' => $user->id]) }}" class="text-decoration-none text-dark">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{-- Optional: Display the latest message snippet --}}
                                {{ optional($user->latestMessage)->content ?? 'No messages yet.' }}
                            </small>
                        </div>
                    </a>
                </li>
            @empty
                <p class="text-muted">No conversations yet. Start a new one!</p>
            @endforelse
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="col-md-9">
        <div class="p-3 border chat-area" style="height: 500px; overflow-y: scroll;">
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
        <form id="message-form" class="mt-3">
            <input type="text" id="message-input" placeholder="Type a message..." class="form-control">
            <input type="hidden" id="receiver_id" value="{{ $activeUser ? $activeUser->id : '' }}">
            <button type="button" id="send-message" class="btn btn-primary mt-2">Send</button>
        </form>
    </div>

</div>

<!-- Include Axios Library -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const sendButton = document.getElementById('send-message');
    sendButton.onclick = function () {
        const messageInput = document.getElementById('message-input');
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
            const messageContainer = document.querySelector('.chat-area');
            messageContainer.innerHTML += `
                <div class="mb-2 text-end">
                    <strong>You:</strong>
                    <p>${response.data.message.content}</p>
                    <small class="text-muted">${response.data.message.time}</small>
                </div>
            `;
            messageInput.value = '';
            messageContainer.scrollTop = messageContainer.scrollHeight;
        })
        .catch((error) => {
            console.error('Error sending message:', error);
        });
    };
</script>
@endsection
