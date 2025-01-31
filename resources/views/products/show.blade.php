@extends('layouts.navlayout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            @if($product->images->isNotEmpty())
            <div id="productImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner border rounded shadow-sm">
                    @foreach ($product->images as $key => $image)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->title }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
                <img src="{{ asset('default-product.png') }}" class="d-block w-100 border rounded shadow-sm" alt="{{ $product->title }}">
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="product-title text-dark fw-bold">{{ $product->title }}</h1>

            @if ($product->reviews->isNotEmpty())
                <p class="text-muted">
                    <strong>Rating:</strong>
                    {{ number_format($product->reviews->avg('rating'), 1) }} / 5 ({{ $product->reviews->count() }} reviews)
                </p>
            @endif

            <p class="text-secondary mt-3">{{ $product->description }}</p>
            <h3 class="text-primary fw-bold">₹{{ number_format($product->price, 2) }}</h3>

            @if (Auth::id() !== $product->user_id)
                <!-- Contact Button (Only for users viewing other people's products) -->
                <form method="POST" action="{{ route('messages.startConversation') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $product->user->id }}">
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Contact</button>
                </form>
            @endif

            <ul class="list-unstyled mt-4">
                <li><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</li>
            </ul>
        </div>

    <!-- Product Tabs -->
    <div class="mt-5">
        <ul class="nav nav-tabs" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="company-overview-tab" data-bs-toggle="tab" data-bs-target="#company-overview" type="button" role="tab">Company Overview</button>
            </li>
        </ul>
        <div class="tab-content p-4 border rounded-bottom shadow-sm" id="productTabsContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <p>{{ $product->description }}</p>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <h4 class="text-primary">Customer Reviews</h4>
                @forelse ($product->reviews as $review)
                    <div class="mb-3 border-bottom pb-3">
                        <h5>{{ $review->user->name ?? 'Anonymous' }} - <span class="text-warning">{{ $review->rating }} / 5</span></h5>
                        <p>{{ $review->review }}</p>
                        <p class="text-muted small">Reviewed on {{ $review->created_at->format('M d, Y') }}</p>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                @endforelse
                <form method="POST" action="{{ route('products.storeReview', $product->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rate This Product</label>
                        <div class="bar-rating-container">
                            <input id="bar-5" type="radio" name="rating" value="5" hidden>
                            <label for="bar-5" class="bar" data-tooltip="5 - Excellent"></label>
                            
                            <input id="bar-4" type="radio" name="rating" value="4" hidden>
                            <label for="bar-4" class="bar" data-tooltip="4 - Good"></label>
                            
                            <input id="bar-3" type="radio" name="rating" value="3" hidden>
                            <label for="bar-3" class="bar" data-tooltip="3 - Average"></label>
                            
                            <input id="bar-2" type="radio" name="rating" value="2" hidden>
                            <label for="bar-2" class="bar" data-tooltip="2 - Poor"></label>
                            
                            <input id="bar-1" type="radio" name="rating" value="1" hidden>
                            <label for="bar-1" class="bar" data-tooltip="1 - Very Poor"></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review" class="form-label">Your Review</label>
                        <textarea id="review" name="review" class="form-control" rows="3" placeholder="Write your review here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
                <link rel="stylesheet" href="{{ asset('css/rating.css') }}">
            </div>
            <div class="tab-pane fade" id="company-overview" role="tabpanel">
                <h4 class="text-primary">Company Overview</h4>
                @if ($product->user)
                    <p>
                        <strong>Company:</strong> 
                        <a href="{{ route('users.show', ['id' => $product->user->id]) }}" class="text-decoration-none">
                            {{ $product->user->name }}
                        </a>
                    </p>                    
                    <p><strong>About Us:</strong> {{ $product->user->about_us }}</p>
                    <p><strong>Contact:</strong> {{ $product->user->phone ?? 'N/A' }}</p>                    
                    <p><strong>Address:</strong> {{ $product->user->address ?? 'Not provided' }}</p>
                @else
                    <p class="text-muted">No information available about the uploader.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

