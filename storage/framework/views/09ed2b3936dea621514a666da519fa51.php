

<?php $__env->startSection('title', 'Search Results - Online Marketplace'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Search Results for "<?php echo e($query); ?>"</h2>

    <!-- Product Results -->
    <h2 class="mb-3">Products</h2>
    <?php if($products->isEmpty()): ?>
        <p>No products found.</p>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="mb-4 card">
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

    <!-- User Results -->
    <h2 class="mb-3">Users</h2>
    <?php if($users->isEmpty()): ?>
        <p>No users found.</p>
    <?php else: ?>
        <div class="list-group">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?php echo e($user->name); ?></h5>
                        <p class="mb-1"><?php echo e(Str::limit($user->about_us, 100)); ?></p>
                        <small class="text-muted"><?php echo e($user->email); ?></small>
                    </div>
                    <div>
                        <!-- View Profile Button -->
                        <a href="<?php echo e(route('users.show', $user->id)); ?>" class="btn btn-sm btn-info">View Profile</a>
                        <!-- Message Button -->
                        <form method="POST" action="<?php echo e(route('messages.startConversation')); ?>" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="receiver_id" value="<?php echo e($user->id); ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Message</button>
                        </form>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach click event to all Message buttons
        document.querySelectorAll('.start-conversation-btn').forEach(button => {
            button.addEventListener('click', function () {
                const receiverId = this.getAttribute('data-receiver-id');
                const content = "Hello!"; // Default initial message content (optional)

                // Send AJAX request to start a conversation
                fetch('<?php echo e(route("conversations.start")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        content: content,
                    }),
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect to the messaging interface for the selected user
                        window.location.href = '<?php echo e(route("messages.index")); ?>?user_id=' + receiverId;
                    } else {
                        alert('Failed to start the conversation. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/search/results.blade.php ENDPATH**/ ?>