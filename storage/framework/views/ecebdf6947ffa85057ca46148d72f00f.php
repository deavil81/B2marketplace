

<?php $__env->startSection('title', 'Home - Online Marketplace'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="text-center jumbotron">
        <h1 class="display-4">Welcome to the Online Marketplace</h1>
        <p class="lead">Find the best products and services just for you!</p>
    </div>

    <h2>Products You May Like</h2>
    <?php if(isset($suggestedProducts) && $suggestedProducts->isNotEmpty()): ?>
        <div class="row">
            <?php $__currentLoopData = $suggestedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-4 col-md-3">
                    <div class="card h-100">
                        <?php if($product->images && $product->images->isNotEmpty()): ?>
                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('default-product.png')); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo e($product->title); ?></h5>
                            <p class="card-text"><?php echo e(Str::limit($product->description, 100)); ?></p>
                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="mt-auto btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <p>No product suggestions available.</p>
    <?php endif; ?>

    <h2 class="mt-4">Search By Categories</h2>
    <?php if(isset($categories) && $categories->isNotEmpty()): ?>
        <div class="row">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-4 col-md-3">
                    <div class="card h-100">
                        <div class="text-center card-body">
                            <h5 class="card-title"><?php echo e($category->name); ?></h5>
                            <a href="<?php echo e(route('categories.show', $category->id)); ?>" class="btn btn-success">Explore <?php echo e($category->name); ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <p>No categories available.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/message.blade.php ENDPATH**/ ?>