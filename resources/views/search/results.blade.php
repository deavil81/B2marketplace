@extends('layouts.app')

@section('title', 'Search Results - Online Marketplace')

@section('content')
<div class="container">
    <h2 class="mb-4">Search Results for "{{ $query }}"</h2>

    <h2 class="mb-3">Products</h2>
    @if ($products->isEmpty())
        <p>No products found.</p>
    @else
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card mb-4">
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

    <h2 class="mb-3">Users</h2>
    @if ($users->isEmpty())
        <p>No users found.</p>
    @else
        <div class="list-group">
            @foreach ($users as $user)
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <small>{{ $user->email }}</small>
                    </div>
                    <p class="mb-1">{{ Str::limit($user->about_us, 100) }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection