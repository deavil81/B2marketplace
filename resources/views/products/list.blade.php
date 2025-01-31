@extends('layouts.navlayout')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Products</h1>
    <div class="row">
        <!-- Filters Section -->
        <div class="col-md-3">
            <form method="GET" action="{{ route('products.list') }}">
                <div class="card p-3 mb-4">
                    <h5 class="card-title">Filters</h5>

                    <!-- Categories Filter -->
                    <div class="mb-3">
                        <h6>Categories</h6>
                        <select name="category" id="category_id" class="form-select" onchange="fetchSubcategories()">
                            <option value="" selected>All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategories Filter -->
                    <div class="mb-3">
                        <h6>Subcategories</h6>
                        <select name="subcategory" id="subcategory_id" class="form-select">
                            <option value="" selected>Select a subcategory</option>
                            @if (request('category') && isset($categories))
                                @foreach ($categories as $category)
                                    @if ($category->id == request('category'))
                                        @foreach ($category->subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" 
                                                {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-3">
                        <h6>Price Range</h6>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Min</span>
                            <input type="number" name="min_price" class="form-control" 
                                value="{{ request('min_price') }}" placeholder="₹0">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Max</span>
                            <input type="number" name="max_price" class="form-control" 
                                value="{{ request('max_price') }}" placeholder="₹10000">
                        </div>
                    </div>

                    <!-- Ratings Filter -->
                    <div class="mb-3">
                        <h6>Ratings</h6>
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="form-check">
                                <input type="radio" name="ratings" value="{{ $i }}" class="form-check-input" 
                                    {{ request('ratings') == $i ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $i }} Stars & Above
                                </label>
                            </div>
                        @endfor
                    </div>

                    <!-- Sorting -->
                    <div class="mb-3">
                        <h6>Sort By</h6>
                        <select name="sort_by" class="form-select">
                            <option value="">Default</option>
                            <option value="price_low_high" {{ request('sort_by') == 'price_low_high' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="price_high_low" {{ request('sort_by') == 'price_high_low' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                            <option value="new_arrivals" {{ request('sort_by') == 'new_arrivals' ? 'selected' : '' }}>
                                New Arrivals
                            </option>
                            <option value="bestselling" {{ request('sort_by') == 'bestselling' ? 'selected' : '' }}>
                                Bestselling
                            </option>
                        </select>
                    </div>

                    <!-- Apply Filters Button -->
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Products Section -->
        <div class="col-md-9">
            @if($products->isNotEmpty())
                <div class="row">
                    @foreach ($products as $product)
                        <div class="mb-4 col-md-4">
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
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }} <!-- Pagination links -->
                </div>
            @else
                <p class="text-center">No products found.</p>
            @endif
        </div>
    </div>
</div>

<!-- Add JavaScript for Dynamic Subcategories -->
<script>
    window.fetchSubcategories = function fetchSubcategories() {
        const categoryId = document.getElementById('category_id').value;
        const subcategorySelect = document.getElementById('subcategory_id');

        if (!categoryId) return;

        subcategorySelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        subcategorySelect.disabled = true;

        fetch(`/categories/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="" disabled selected>Select a subcategory</option>';
                if (data.length > 0) {
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subcategorySelect.appendChild(option);
                    });
                } else {
                    subcategorySelect.innerHTML = '<option value="" disabled selected>No subcategories available</option>';
                }
                subcategorySelect.disabled = false;
            })
            .catch(() => {
                subcategorySelect.innerHTML = '<option value="" disabled selected>Error loading subcategories</option>';
                subcategorySelect.disabled = false;
            });
    };
</script>
@endsection
