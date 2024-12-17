@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if (isset($user))
                    <h1>Welcome, {{ $user->name }}</h1>
                    <p>Here you can see an overview of your activities and manage your account.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Profile</h5>
                                    <p class="card-text">View and edit your profile information.</p>
                                    <a href="{{ route('profile') }}" class="btn btn-primary">Go to Profile</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Settings</h5>
                                    <p class="card-text">Manage your account settings.</p>
                                    <a href="#" class="btn btn-primary">Go to Settings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Logout</h5>
                                    <p class="card-text">Logout from your account.</p>
                                    <a href="#" class="btn btn-danger">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p>User information is not available.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
