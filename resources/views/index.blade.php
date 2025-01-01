@extends('layouts.navlayout')

@section('title', 'Home')

@section('content')
    <div class="text-center jumbotron">
        <h1 class="display-4">Welcome to the Online Marketplace</h1>
        <p class="lead">Find the best products and services just for you!</p>
    </div>

    <h2>Suggested Products for You</h2>
    @if(isset($suggestedProducts) && $suggestedProducts->isNotEmpty())
        <div class="row">
            @foreach ($suggestedProducts as $product)
                <div class="mb-4 col-md-3">
                    <div class="card h-100">
                        @if($product->images && $product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('default-product.png') }}" class="card-img-top" alt="{{ $product->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p>{{ Str::limit($product->description, 100) }}</p>
                            <p><strong>Price:</strong> ₹{{ $product->price }}</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            @php
                $randomProducts = App\Models\Product::inRandomOrder()->limit(8)->get();
            @endphp
            @foreach ($randomProducts as $product)
                <div class="mb-4 col-md-3">
                    <div class="card h-100">
                        @if($product->images && $product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('default-product.png') }}" class="card-img-top" alt="{{ $product->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p>{{ Str::limit($product->description, 100) }}</p>
                            <p><strong>Price:</strong> ₹{{ $product->price }}</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="mt-4">Explore Categories</h2>
    @if(isset($categories) && $categories->isNotEmpty())
        <div class="row">
            @foreach ($categories as $category)
                <div class="mb-4 col-md-3">
                    <div class="card h-100">
                        <div class="text-center card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-success">Explore {{ $category->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No categories available.</p>
    @endif
@endsection
