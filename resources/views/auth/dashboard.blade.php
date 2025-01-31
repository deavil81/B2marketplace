@extends('layouts.navlayout')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h1 class="mt-4">Welcome, {{ $user->name ?? 'Guest' }}</h1>
        <p>Here you can see an overview of your activities and manage your account.</p>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profile</h5>
                        <p class="card-text">View and edit your profile information.</p>
                        <a href="{{ route('profile.index') }}" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Settings</h5>
                        <p class="card-text">Manage your account settings.</p>
                        <a href="{{ route('settings') }}" class="btn btn-primary">Go to Settings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Logout</h5>
                        <p class="card-text">Logout from your account.</p>
                        <a href="{{ route('logout') }}" class="btn btn-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Role-Specific Section --}}
            @if ($user->role === 'buyer')
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My RFQs</h5>
                            <p class="card-text">Create and manage your Requests for Quotations (RFQs).</p>
                            <a href="{{ route('rfq.create') }}" class="btn btn-success">Create RFQ</a>
                            <a href="{{ route('rfq.index') }}" class="btn btn-secondary">View My RFQs</a>
                        </div>
                    </div>
                </div>
            @elseif ($user->role === 'manufacturer')
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Available RFQs</h5>
                            <p class="card-text">Browse and bid on RFQs from buyers.</p>
                            <a href="{{ route('rfq.index') }}" class="btn btn-primary">View RFQs</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
