

<?php $__env->startSection('title', 'conversation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <!-- Chat Box -->
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5>Chat with <?php echo e($user->name); ?></h5>
                    <span class="text-light"><?php echo e($user->email); ?></span>
                </div>
                <div class="card-body chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                    <!-- Messages -->
                    <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex mb-3 <?php echo e($message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start'); ?>">
                            <div class="message p-3 rounded <?php echo e($message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark'); ?>" style="max-width: 70%;">
                                <p class="mb-1"><?php echo e($message->content); ?></p>
                                <small class="text-muted d-block"><?php echo e($message->created_at->format('d M Y, h:i A')); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-center text-muted">No messages yet. Start the conversation!</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <form action="<?php echo e(route('messages.store')); ?>" method="POST" class="d-flex">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="receiver_id" value="<?php echo e($user->id); ?>">
                        <textarea name="content" class="form-control me-2" rows="1" placeholder="Type a message..." required></textarea>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Template-Specific Styles -->
<style>
    .chat-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .message {
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/conversation.blade.php ENDPATH**/ ?>