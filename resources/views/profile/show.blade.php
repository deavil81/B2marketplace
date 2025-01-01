@extends('layouts.navlayout')

@section('title', $user->name . "'s Profile")

@section('content')
<div class="container mt-4">
    <div class="mb-4 text-center profile-header">
        <img src="{{ $user->profile_picture ?? asset('images/default-profile.png') }}" class="rounded-circle" alt="{{ $user->name }}" width="150">
        <h3>{{ $user->name }}</h3>
        <p class="text-muted">@ {{ $user->username }}</p>
        <p>{{ $user->bio ?? 'No bio available' }}</p>
        <form action="{{ route('messages.create') }}" method="POST">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $user->id }}">
            <textarea name="content" placeholder="Type your message here"></textarea>
            <button type="submit">Send</button>
        </form>
        Contact {{ $user->name }}
    </div>

    <div class="text-center profile-stats">
        <div class="row">
            <div class="col">
                <h5>{{ $posts->total() }}</h5>
                <p>Posts</p>
            </div>
            <div class="col">
                <h5>{{ $followersCount }}</h5>
                <p>Followers</p>
            </div>
            <div class="col">
                <h5>{{ $followingCount }}</h5>
                <p>Following</p>
            </div>
        </div>
    </div>

    <hr>

    <div class="profile-posts">
        <h4>Posts</h4>
        <div class="row">
            @forelse($posts as $post)
                <div class="mb-4 col-md-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Post image">
                        <div class="card-body">
                            <p>{{ $post->caption }}</p>
                            <a href="{{ route('post.view', $post->id) }}" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No posts to show.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
