

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <!-- Product Images Carousel -->
        <div class="col-md-6">
            <?php if($product->images->isNotEmpty()): ?>
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="carousel-item <?php echo e($key == 0 ? 'active' : ''); ?>">
                            <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" class="d-block w-100" alt="<?php echo e($product->title); ?>">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <?php else: ?>
                <img src="<?php echo e(asset('default-product.png')); ?>" class="d-block w-100" alt="<?php echo e($product->title); ?>">
            <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="product-title"><?php echo e($product->title); ?></h1>
            <?php if($product->reviews->isNotEmpty()): ?>
                <p><strong>Average Rating:</strong> <?php echo e(number_format($product->reviews->avg('rating'), 1)); ?> / 5 (<?php echo e($product->reviews->count()); ?> reviews)</p>
            <?php endif; ?>
            <p class="product-description"><?php echo e($product->description); ?></p>
            <p class="product-price"><strong>Price:</strong> ₹<?php echo e($product->price); ?></p>
            <ul class="product-details">
                <li><strong>Category:</strong> <?php echo e($product->category->name ?? 'N/A'); ?></li>
            </ul>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-4">
        <h3>Product Reviews</h3>
        <?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo e($review->user->name ?? 'Anonymous'); ?> - <span class="text-warning"><?php echo e($review->rating); ?> / 5</span>
                    </h5>
                    <p><small>Reviewed on <?php echo e($review->created_at->format('M d, Y')); ?></small></p>
                    <p class="card-text"><?php echo e($review->review); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No reviews yet. Be the first to review this product!</p>
        <?php endif; ?>
    </div>

    <!-- Submit Review Section -->
    <div class="mt-4">
        <h3>Submit Your Review</h3>
        <form id="review-form" method="POST" action="<?php echo e(route('products.storeReview', $product->id)); ?>">
            <?php echo csrf_field(); ?>
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
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3">
                    <div class="card">
                        <?php if($relatedProduct->thumbnail && Storage::exists('public/' . $relatedProduct->thumbnail->image_path)): ?>
                            <img src="<?php echo e(asset('storage/' . $relatedProduct->thumbnail->image_path)); ?>" class="card-img-top" alt="<?php echo e($relatedProduct->title); ?>">
                        <?php elseif($relatedProduct->images && $relatedProduct->images->isNotEmpty()): ?>
                            <img src="<?php echo e(asset('storage/' . $relatedProduct->images->first()->image_path)); ?>" class="card-img-top" alt="<?php echo e($relatedProduct->title); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('default-product.png')); ?>" class="card-img-top" alt="Default Product Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($relatedProduct->title); ?></h5>
                            <p class="card-text">₹<?php echo e($relatedProduct->price); ?></p>
                            <a href="<?php echo e(route('products.show', $relatedProduct->id)); ?>" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/products/show.blade.php ENDPATH**/ ?>