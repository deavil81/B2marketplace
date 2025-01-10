@extends('layouts.navlayout')

@section('title', 'User Profile')

@section('content')
<div class="container">
    <div class="row">
        <!-- Profile Section -->
        <div class="text-center col-md-3">
            <img 
                src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}" 
                alt="Profile Picture" 
                class="img-thumbnail rounded-circle" 
                style="width: 150px; height: 150px; object-fit: cover;"
            >
            <h4 class="mt-3">{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>
            
            @if(auth()->id() === $user->id)
                <!-- Show only for the logged-in user -->
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            @endif
        </div>

        <!-- Tab Section -->
        <div class="col-md-9">
            <ul class="mb-3 nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#overview" data-bs-toggle="tab">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#products" data-bs-toggle="tab">Products</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview">
                    <h5>Profile Information</h5>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Contact:</strong> {{ $user->contact }}</p>
                    <p><strong>Business Type:</strong> {{ $user->business_type }}</p>
                    <p><strong>Address:</strong> {{ $user->address }}</p>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products">
                    <!-- User Products Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Your Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @forelse ($products as $product)
                                            <div class="mb-4 col-md-4">
                                                <div class="card h-100">
                                                    @if ($product->thumbnail_image_id && $product->images->contains('id', $product->thumbnail_image_id))
                                                        @php
                                                            $thumbnail = $product->images->firstWhere('id', $product->thumbnail_image_id);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $thumbnail->image_path) }}" alt="{{ $product->title }}">
                                                    @elseif ($product->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}">
                                                    @else
                                                        <img src="{{ asset('storage/default-product.png') }}" alt="Default Product Image">
                                                    @endif

                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ $product->title }}</h6>
                                                        <p class="card-text">{{ Str::limit($product->description, 100, '...') }}</p>
                                                        <p><strong>Price:</strong> ₹{{ $product->price }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center text-muted">No products added yet.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
