@extends('layouts.navlayout')

@section('title', 'Home')

@section('content')
    <!-- Add the CSS file -->
    <link rel="stylesheet" href="{{ asset('css/indexstyle.css') }}">

    <div class="text-center jumbotron">
        <h1 class="display-4">Welcome to the Online Marketplace</h1>
        <p class="lead">Find the best products and services just for you!</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Product You May Like</h2>
        <a href="{{ route('products.index') }}" class="text-secondary">View All Products</a>
    </div>
    @if(isset($suggestedProducts) && $suggestedProducts->isNotEmpty())
        <div class="row">
            @foreach ($suggestedProducts as $product)
                <div class="mb-4 col-md-3">
                    <div class="card h-100 shadow-sm">
                        @if($product->images && $product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                class="card-img-top" 
                                alt="{{ $product->title }}" 
                                style="width: 100%; height: auto; max-height: 250px; object-fit: contain;">
                        @else
                            <img src="{{ asset('default-product.jpg') }}" 
                                class="card-img-top" 
                                alt="{{ $product->title }}" 
                                style="width: 100%; height: auto; max-height: 250px; object-fit: contain;">
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
                    <div class="card h-100 shadow-sm">
                        @if($product->images && $product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                class="card-img-top" 
                                alt="{{ $product->title }}" 
                                style="width: 100%; height: auto; max-height: 250px; object-fit: contain;">
                        @else
                            <img src="{{ asset('default-product.jpg') }}" 
                                class="card-img-top" 
                                alt="{{ $product->title }}" 
                                style="width: 100%; height: auto; max-height: 250px; object-fit: contain;">
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
    <div class="row">
        @php
            // Define default images for each category
            $defaultImages = [
                'Electronics' => 'images/default-electronics.jpg',
                'Clothing' => 'images/default-clothing.jpg',
                'Chemicals' => 'images/default-chemicals.jpg',
                'Machinery' => 'images/default-machinery.jpg',
                'Food & Beverages' => 'images/default-food.jpg',
                'Packaging Material' => 'images/default-packaging.jpg',
                'Industrial' => 'images/default-industrial.png',
                'Building Materials' => 'images/default-building.jpg',
                'Pharmaceuticals' => 'images/default-pharmaceuticals.jpg',
                'Hospital Equipment' => 'images/default-hospital.jpg',
                'Automotive' => 'images/default-automotive.jpg',
                'Furniture' => 'images/default-furniture.jpg',
                'Toys' => 'images/default-toys.jpg',
                'Books' => 'images/default-books.jpg',
                'Sports' => 'images/default-sports.jpg',
                'Beauty & Personal Care' => 'images/default-beauty.jpg',
                'Home Appliances' => 'images/default-home-appliances.jpg',
                'Jewelry' => 'images/default-jewelry.jpg',
                'Office Supplies' => 'images/default-office-supplies.jpg',
                'Pet Supplies' => 'images/default-pet-supplies.jpg',
                
            ];
        @endphp

        @foreach ($categories as $category)
            <div class="mb-4 col-md-4">
                <div class="card h-100 shadow-sm">
                    {{-- Use the category's image or a default image from the mapping --}}
                    @php
                        $imagePath = $category->image 
                            ? 'images/' . $category->image 
                            : ($defaultImages[$category->name] ?? 'images/default-image.jpg');
                    @endphp
                    <img src="{{ asset($imagePath) }}" 
                        class="card-img-top" 
                        alt="{{ $category->name }}" 
                        style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <ul class="list-unstyled subcategory-list">
                            @forelse ($category->subcategories->unique('name') ?? [] as $subcategory)
                                <li>{{ $subcategory->name }}</li>
                            @empty
                                <li>No subcategories available.</li>
                            @endforelse
                        </ul>
                        <a href="{{ url('categories/' . $category->id) }}" class="btn btn-primary mt-2">View All</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

@endsection