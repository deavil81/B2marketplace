@extends('layouts.app')

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
                                <div class="carousel-caption d-none d-md-block">
                                    <p>Image {{ $key + 1 }} of {{ $product->images->count() }}</p>
                                </div>
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
                <li><strong>Material:</strong> Cotton</li>
                <li><strong>Size:</strong> Medium</li>
            </ul>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-4">
        <h3>Product Reviews</h3>
        @forelse ($product->reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $review->user->name }} - <span class="text-warning">{{ $review->rating }} / 5</span>
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
                <div class="rating" style="width: 10rem">
                    <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                    <input id="rating-4" type="radio" name="rating" value="4"  /><label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                    <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                    <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                    <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
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
        direction: rtl;
        unicode-bidi: bidi-override;
        color: #ddd; /* Personal choice */
        font-size: 8px;
        margin-left: -15px;
    }
    .rating input {
        display: none;
    }
    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked + label,
    .rating input:checked + label ~ label {
        color: #ffc107; /* Personal color choice. Lifted from Bootstrap 4 */
        font-size: 8px;
    }


    .front-stars, .back-stars, .star-rating {
    display: flex;
  }
  
  .star-rating {
    align-items: left;
    font-size: 1.5em;
    justify-content: left;
    margin-left: -5px;
  }
  
  .back-stars {
    color: #CCC;
    position: relative;
  }
  
  .front-stars {
    color: #FFBC0B;
    overflow: hidden;
    position: absolute;
    top: 0;
    transition: all 0.5s;
  }

  
  .percent {
    color: #bb5252;
    font-size: 1.5em;
  }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-rating .star'); // Match `.star-rating` class
    const ratingInput = document.getElementById('rating');
    const ratingDisplay = document.getElementById('rating-display');

    let selectedRating = 0;

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            resetStars();
            highlightStars(index);
        });

        star.addEventListener('mouseleave', () => {
            resetStars();
            if (selectedRating > 0) {
                highlightStars(selectedRating - 1);
            }
        });

        star.addEventListener('click', () => {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            ratingDisplay.textContent = `Rating: ${selectedRating}`;
            resetStars();
            highlightStars(index);
        });
    });

    function highlightStars(index) {
        for (let i = 0; i <= index; i++) {
            stars[i].classList.add('hovered');
        }
    }

    function resetStars() {
        stars.forEach(star => star.classList.remove('hovered', 'selected'));
    }
});
</script>
@endsection
