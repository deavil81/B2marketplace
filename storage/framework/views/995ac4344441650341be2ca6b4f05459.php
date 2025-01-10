

<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
    <div class="text-center jumbotron">
        <h1 class="display-4">Welcome to the Online Marketplace</h1>
        <p class="lead">Find the best products and services just for you!</p>
    </div>

    <h2>Suggested Products for You</h2>
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
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($product->title); ?></h5>
                            <p><?php echo e(Str::limit($product->description, 100)); ?></p>
                            <p><strong>Price:</strong> ₹<?php echo e($product->price); ?></p>
                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="row">
            <?php
                $randomProducts = App\Models\Product::inRandomOrder()->limit(8)->get();
            ?>
            <?php $__currentLoopData = $randomProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-4 col-md-3">
                    <div class="card h-100">
                        <?php if($product->images && $product->images->isNotEmpty()): ?>
                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('default-product.png')); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($product->title); ?></h5>
                            <p><?php echo e(Str::limit($product->description, 100)); ?></p>
                            <p><strong>Price:</strong> ₹<?php echo e($product->price); ?></p>
                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <h2 class="mt-4">Explore Categories</h2>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/index.blade.php ENDPATH**/ ?>