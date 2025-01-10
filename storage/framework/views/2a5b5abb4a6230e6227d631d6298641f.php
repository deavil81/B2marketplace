

<?php $__env->startSection('title', 'User Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <!-- Profile Section -->
        <div class="text-center col-md-3">
            <img 
                src="<?php echo e($user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png')); ?>" 
                alt="Profile Picture" 
                class="img-thumbnail rounded-circle" 
                style="width: 150px; height: 150px; object-fit: cover;"
            >
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
                    <!-- User Products Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Your Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="mb-4 col-md-4">
                                                <div class="card h-100">
                                                    <?php if($product->thumbnail_image_id && $product->images->contains('id', $product->thumbnail_image_id)): ?>
                                                        <?php
                                                            $thumbnail = $product->images->firstWhere('id', $product->thumbnail_image_id);
                                                        ?>
                                                        <img src="<?php echo e(asset('storage/' . $thumbnail->image_path)); ?>" alt="<?php echo e($product->title); ?>">
                                                    <?php elseif($product->images->isNotEmpty()): ?>
                                                        <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" alt="<?php echo e($product->title); ?>">
                                                    <?php else: ?>
                                                        <img src="<?php echo e(asset('storage/default-product.png')); ?>" alt="Default Product Image">
                                                    <?php endif; ?>

                                                    <div class="card-body">
                                                        <h6 class="card-title"><?php echo e($product->title); ?></h6>
                                                        <p class="card-text"><?php echo e(Str::limit($product->description, 100, '...')); ?></p>
                                                        <p><strong>Price:</strong> ₹<?php echo e($product->price); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="col-12">
                                                <p class="text-center text-muted">No products added yet.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <?php echo e($products->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/users/profile.blade.php ENDPATH**/ ?>