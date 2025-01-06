@extends('layouts.navlayout')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Product Images Carousel -->
        <div class="col-md-6">
            @if($product->images->isNotEmpty())
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($product->images as $key => $image)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->title }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
                <img src="{{ asset('default-product.png') }}" class="d-block w-100" alt="{{ $product->title }}">
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="product-title">{{ $product->title }}</h1>
            @if ($product->reviews->isNotEmpty())
                <p><strong>Average Rating:</strong> {{ number_format($product->reviews->avg('rating'), 1) }} / 5 ({{ $product->reviews->count() }} reviews)</p>
            @endif
            <p class="product-description">{{ $product->description }}</p>
            <p class="product-price"><strong>Price:</strong> ₹{{ $product->price }}</p>
            <ul class="product-details">
                <li><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</li>
            </ul>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-4">
        <h3>Product Reviews</h3>
        @forelse ($product->reviews as $review)
            <div class="mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $review->user->name ?? 'Anonymous' }} - <span class="text-warning">{{ $review->rating }} / 5</span>
                    </h5>
                    <p><small>Reviewed on {{ $review->created_at->format('M d, Y') }}</small></p>
                    <p class="card-text">{{ $review->review }}</p>
                </div>
            </div>
        @empty
            <p>No reviews yet. Be the first to review this product!</p>
        @endforelse
    </div>

    <!-- Submit Review Section -->
    <div class="mt-4">
        <h3>Submit Your Review</h3>
        <form id="review-form" method="POST" action="{{ route('products.storeReview', $product->id) }}">
            @csrf
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <div class="rating">
                    <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-star"></i></label>
                    <input id="rating-4" type="radio" name="rating" value="4"/><label for="rating-4"><i class="fas fa-star"></i></label>
                    <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-star"></i></label>
                    <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-star"></i></label>
                    <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-star"></i></label>
                </div>                
                <input type="hidden" name="rating" id="rating" value="0">
                <p id="rating-display" class="mt-2">Rating: 0</p>
            </div>
            <div class="mb-3">
                <label for="review" class="form-label">Review</label>
                <textarea id="review" name="review" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>

    <!-- Related Products Section -->
    <div class="mt-5">
        <h3>Related Products</h3>
        <div class="row">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="col-md-3">
                    <div class="card">
                        @if ($relatedProduct->thumbnail && Storage::exists('public/' . $relatedProduct->thumbnail->image_path))
                            <img src="{{ asset('storage/' . $relatedProduct->thumbnail->image_path) }}" class="card-img-top" alt="{{ $relatedProduct->title }}">
                        @elseif ($relatedProduct->images && $relatedProduct->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $relatedProduct->images->first()->image_path) }}" class="card-img-top" alt="{{ $relatedProduct->title }}">
                        @else
                            <img src="{{ asset('default-product.png') }}" class="card-img-top" alt="Default Product Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->title }}</h5>
                            <p class="card-text">₹{{ $relatedProduct->price }}</p>
                            <a href="{{ route('products.show', $relatedProduct->id) }}" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .rating {
        display: flex;
        direction: rtl;
        unicode-bidi: bidi-override;
    }
    .rating input {
        display: none;
    }
    .rating label {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.rating label');
    const ratingInput = document.getElementById('rating');
    const ratingDisplay = document.getElementById('rating-display');

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const rating = 5 - index;
            ratingInput.value = rating;
            ratingDisplay.textContent = `Rating: ${rating}`;
        });
    });
});
</script>
@endsection
