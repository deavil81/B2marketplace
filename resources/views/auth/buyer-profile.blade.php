@extends('layouts.navlayout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Profile Card -->
        <div class="mb-4 col-md-3">
            <div class="shadow-sm card widget-card border-light">
                <div class="text-center d-flex justify-content-center align-items-center card-body">
                    <img src="{{ asset('storage/' . (auth()->user()->profile_picture ?? 'default-avatar.png')) }}" 
                         class="mb-3 rounded-circle img-thumbnail" alt="Profile Picture" style="width: 150px; height: 150px;">
                </div>
                <div class="text-center card-body">
                    <h4 class="card-title">{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <button class="mt-2 btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card" style="max-height: 500px; overflow: auto;">
                <div class="text-white card-header bg-primary">
                    <h2>Details</h2>
                </div>
                <div class="card-body">
                    <!-- Navigation Menu -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#overview" data-bs-toggle="tab">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#profile" data-bs-toggle="tab">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#password" data-bs-toggle="tab">Password</a>
                        </li>
                    </ul>
                    <div class="p-4 tab-content">
                        <div class="tab-pane active" id="overview">
                            <h4 class="mb-3">About Us</h4>
                            <p class="text-start">{{ auth()->user()->about_us }}</p>
                        </div>
                        <div class="tab-pane" id="profile">
                            <h4 class="mt-4 mb-3">Buyer Details</h4>
                            @if(auth()->user()->buyer)
                                <p class="text-start"><strong>Company Name:</strong> {{ auth()->user()->buyer->company_name }}</p>
                                <p class="text-start"><strong>Address:</strong> {{ auth()->user()->buyer->address }}</p>
                                <p class="text-start"><strong>Phone Number:</strong> {{ auth()->user()->buyer->phone }}</p>
                                <p class="text-start"><strong>Industry:</strong> {{ auth()->user()->buyer->industry }}</p>
                                <p class="text-start"><strong>Registration Date:</strong> {{ auth()->user()->buyer->created_at->format('d M, Y') }}</p>
                                <p class="text-start"><strong>Additional Notes:</strong> {{ auth()->user()->buyer->notes ?? 'N/A' }}</p>
                            @else
                                <p class="text-start">No buyer-specific details found.</p>
                            @endif
                        </div>
                        <div class="tab-pane" id="emails">
                            <!-- Emails Section -->
                        </div>
                        <div class="tab-pane" id="password">
                            <!-- Password Section -->
                            <h4 class="mt-4 mb-3">Update Password</h4>
                            <form action="{{ route('password.update') }}" method="POST">
                                @csrf
                                <div class="mb-3 form-group text-start">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="mb-3 form-group text-start">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <div class="mb-3 form-group text-start">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="documents">
        <h4 class="mt-4 mb-3">Uploaded Documents</h4>
        @if(auth()->user()->documents && auth()->user()->documents->isNotEmpty())
            <ul class="list-group">
                @foreach(auth()->user()->documents as $document)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ asset('storage/' . $document->path) }}" target="_blank">{{ $document->name }}</a>
                        <span class="badge bg-primary rounded-pill">{{ $document->created_at->format('d M, Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-start">No documents uploaded.</p>
        @endif
    </div>
@endsection
