

<?php $__env->startSection('title', 'Messages'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Your Messages</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h5>Unread Messages: <?php echo e($unreadCount); ?></h5>
    </div>

    <div class="list-group">
        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="list-group-item">
                <strong><?php echo e($message->sender->name); ?></strong>: 
                <?php echo e(Str::limit($message->content, 50)); ?>

                <small class="text-muted float-right"><?php echo e($message->created_at->diffForHumans()); ?></small>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/index.blade.php ENDPATH**/ ?>