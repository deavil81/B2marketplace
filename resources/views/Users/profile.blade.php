@extends('layouts.navlayout')

@section('title', 'User Profile')

@section('content')
<div class="container">
    <div class="row">
        <!-- Profile Section -->
        <div class="text-center col-md-3">
            <img src="{{ asset('path/to/profile-pic.jpg') }}" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px;">
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
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5>Your Products</h5>
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('products.create') }}" class="btn btn-success">Add New Product</a>
                        @endif
                    </div>

                    <div class="row">
                        @forelse($products as $product)
                            <div class="mb-3 col-md-4">
                                <div class="card">
                                    <img src="{{ $product->image_path ? asset($product->image_path) : asset('default-product.png') }}" class="card-img-top" alt="{{ $product->title }}">
                                    <div class="text-center card-body">
                                        <h6 class="card-title">{{ $product->title }}</h6>
                                        <p class="card-text text-muted">{{ $product->description }}</p>
                                        <p class="card-text"><strong>Price:</strong> ₹{{ $product->price }}</p>

                                        @if(auth()->id() === $user->id)
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center">
                                <p>No products available at the moment.</p>
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('products.create') }}" class="btn btn-success">Add Your First Product</a>
                                @endif
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
