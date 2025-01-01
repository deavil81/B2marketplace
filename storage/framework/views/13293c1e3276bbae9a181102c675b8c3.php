

<?php $__env->startSection('title', 'Messaging'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar: List of Conversations -->
        <div class="col-md-4 border-end">
            <h5>Conversations</h5>
            <ul class="list-group">
                <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="list-group-item <?php echo e(isset($activeUser) && $conversation->id === $activeUser->id ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('messages.index', ['user_id' => $conversation->id])); ?>" class="text-decoration-none text-dark">
                            <strong><?php echo e($conversation->name); ?></strong><br>
                            <small class="text-muted"><?php echo e($conversation->lastMessage ?? 'No messages yet'); ?></small>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="list-group-item">No conversations found.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Main Chat Section -->
        <div class="col-md-8">
            <?php if(isset($activeUser) && isset($messages)): ?>
                <h5>Chat with <?php echo e($activeUser->name); ?></h5>
                <div class="border p-3 chat-window" style="height: 60vh; overflow-y: scroll;">
                    <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mb-3 <?php echo e($message->sender_id === auth()->id() ? 'text-end' : 'text-start'); ?>">
                            <p class="mb-1 <?php echo e($message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light'); ?> p-2 rounded">
                                <?php echo e($message->content); ?>

                            </p>
                            <small class="text-muted"><?php echo e($message->created_at->format('d M Y, h:i A')); ?></small>
                        </div>
                        <div class="col-md-8 text-center">
                            <h5>Start a Conversation</h5>
                            <form action="<?php echo e(route('messages.startConversation')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="input-group mb-3">
                                    <select name="receiver_id" class="form-control" required>
                                        <option value="" disabled selected>Select a user...</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <textarea name="content" class="form-control" rows="1" placeholder="Type a message..." required></textarea>
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p>No messages in this conversation yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Message Input -->
                <form action="<?php echo e(route('messages.store')); ?>" method="POST" class="mt-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="receiver_id" value="<?php echo e($activeUser->id); ?>">
                    <div class="input-group">
                        <textarea name="content" class="form-control" rows="1" placeholder="Type a message..." required></textarea>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center">Select a conversation to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/messages.blade.php ENDPATH**/ ?>