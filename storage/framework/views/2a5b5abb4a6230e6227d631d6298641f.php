

<?php $__env->startSection('title', 'User Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <!-- Profile Section -->
        <div class="text-center col-md-3">
            <img src="<?php echo e(asset('path/to/profile-pic.jpg')); ?>" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px;">
            <h4 class="mt-3"><?php echo e($user->name); ?></h4>
            <p class="text-muted"><?php echo e($user->email); ?></p>
            
            <?php if(auth()->id() === $user->id): ?>
                <!-- Show only for the logged-in user -->
                <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">Edit Profile</a>
            <?php endif; ?>
        </div>

        <!-- Tab Section -->
        <div class="col-md-9">
            <ul class="mb-3 nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#overview" data-bs-toggle="tab">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#products" data-bs-toggle="tab">Products</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview">
                    <h5>Profile Information</h5>
                    <p><strong>Name:</strong> <?php echo e($user->name); ?></p>
                    <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                    <p><strong>Contact:</strong> <?php echo e($user->contact); ?></p>
                    <p><strong>Business Type:</strong> <?php echo e($user->business_type); ?></p>
                    <p><strong>Address:</strong> <?php echo e($user->address); ?></p>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5>Your Products</h5>
                        <?php if(auth()->id() === $user->id): ?>
                            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-success">Add New Product</a>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="mb-3 col-md-4">
                                <div class="card">
                                    <img src="<?php echo e($product->image_path ? asset($product->image_path) : asset('default-product.png')); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                                    <div class="text-center card-body">
                                        <h6 class="card-title"><?php echo e($product->title); ?></h6>
                                        <p class="card-text text-muted"><?php echo e($product->description); ?></p>
                                        <p class="card-text"><strong>Price:</strong> ₹<?php echo e($product->price); ?></p>

                                        <?php if(auth()->id() === $user->id): ?>
                                            <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" style="display: inline-block;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center">
                                <p>No products available at the moment.</p>
                                <?php if(auth()->id() === $user->id): ?>
                                    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-success">Add Your First Product</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($products->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/users/profile.blade.php ENDPATH**/ ?>