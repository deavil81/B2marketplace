@extends('layouts.navlayout')

@section('title', 'Search Results - Online Marketplace')

@section('content')
<div class="container">
    <h2 class="mb-4">Search Results for "{{ $query }}"</h2>

    <!-- Product Results -->
    <h2 class="mb-3">Products</h2>
    @if ($products->isEmpty())
        <p>No products found.</p>
    @else
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="mb-4 card">
                        @if ($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('default-product.png') }}" class="card-img-top" alt="{{ $product->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text"><strong>₹{{ $product->price }}</strong></p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- User Results -->
    <h2 class="mb-3">Users</h2>
    @if ($users->isEmpty())
        <p>No users found.</p>
    @else
        <div class="list-group">
            @foreach ($users as $user)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <!-- User Profile Photo -->
                        <img 
                            src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}" 
                            alt="Profile Picture" 
                            class="img-thumbnail rounded-circle me-3" 
                            style="width: 50px; height: 50px; object-fit: cover;"
                        >

                        <!-- User Info -->
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="mb-1">{{ Str::limit($user->about_us, 100) }}</p>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                    <div>
                        <!-- View Profile Button -->
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">View Profile</a>
                        <!-- Message Button -->
                        <form method="POST" action="{{ route('messages.startConversation') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-sm btn-primary">Message</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach click event to all Message buttons
        document.querySelectorAll('.start-conversation-btn').forEach(button => {
            button.addEventListener('click', function () {
                const receiverId = this.getAttribute('data-receiver-id');
                const content = "Hello!"; // Default initial message content (optional)

                // Send AJAX request to start a conversation
                fetch('{{ route("conversations.start") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        content: content,
                    }),
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to the messaging interface for the selected user
                        window.location.href = '{{ route("messages.index") }}?user_id=' + receiverId;
                    } else {
                        alert('Failed to start the conversation. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>
