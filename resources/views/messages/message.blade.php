@extends('layouts.message')

@section('title', 'Home - Online Marketplace')

@section('content')
<div class="container mt-4">
    <div class="text-center jumbotron">
        <h1 class="display-4">Welcome to the Online Marketplace</h1>
        <p class="lead">Find the best products and services just for you!</p>
    </div>

    <h2>Products You May Like</h2>
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
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <a href="{{ route('products.show', $product->id) }}" class="mt-auto btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No product suggestions available.</p>
    @endif

    <h2 class="mt-4">Search By Categories</h2>
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
</div>
@endsection
