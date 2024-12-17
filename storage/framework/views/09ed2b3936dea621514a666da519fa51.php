

<?php $__env->startSection('title', 'Search Results - Online Marketplace'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Search Results for "<?php echo e($query); ?>"</h2>

    <h2 class="mb-3">Products</h2>
    <?php if($products->isEmpty()): ?>
        <p>No products found.</p>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <?php if($product->images->isNotEmpty()): ?>
                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('default-product.png')); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($product->title); ?></h5>
                            <p class="card-text"><?php echo e(Str::limit($product->description, 100)); ?></p>
                            <p class="card-text"><strong>₹<?php echo e($product->price); ?></strong></p>
                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <h2 class="mb-3">Users</h2>
    <?php if($users->isEmpty()): ?>
        <p>No users found.</p>
    <?php else: ?>
        <div class="list-group">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo e($user->name); ?></h5>
                        <small><?php echo e($user->email); ?></small>
                    </div>
                    <p class="mb-1"><?php echo e(Str::limit($user->about_us, 100)); ?></p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/search/results.blade.php ENDPATH**/ ?>