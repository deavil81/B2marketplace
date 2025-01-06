

<?php $__env->startSection('title', 'Messaging'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row">
        <!-- Conversations List Sidebar -->
        <div class="col-md-4 border-end">
            <h5>Conversations</h5>
            <ul class="list-group">
                <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $chatPartner = $conversation->user1_id === auth()->id() ? $conversation->user2 : $conversation->user1;
                    ?>
                    <li class="list-group-item <?php echo e($activeUser && $activeUser->id === $chatPartner->id ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('messages.index', ['user_id' => $chatPartner->id])); ?>" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img src="<?php echo e(asset($chatPartner->profile_picture ?? 'default-avatar.png')); ?>" alt="<?php echo e($chatPartner->name); ?>" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                                </div>
                                <div>
                                    <strong><?php echo e($chatPartner->name); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo e($conversation->messages->last()->content ?? 'No messages yet.'); ?>

                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        <!-- Chat Panel -->
        <div class="col-md-8">
            <?php if($activeUser): ?>
                <div class="mb-3 chat-header d-flex align-items-center">
                    <div class="avatar me-3">
                        <img src="<?php echo e(asset($activeUser->profile_picture ?? 'default-avatar.png')); ?>" alt="<?php echo e($activeUser->name); ?>" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                    </div>
                    <h5 class="mb-0"><?php echo e($activeUser->name); ?></h5>
                </div>

                <!-- Messages Display -->
                <div id="chat-messages" class="p-3 mb-3 border chat-messages" style="height: 400px; overflow-y: scroll;">
                    <?php if($messages && $messages->count()): ?>
                        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-2 <?php echo e($message->sender_id === auth()->id() ? 'text-end' : ''); ?>">
                                <strong><?php echo e($message->sender_id === auth()->id() ? 'You' : $activeUser->name); ?>:</strong>
                                <p><?php echo e($message->content); ?></p>
                                <small class="text-muted"><?php echo e($message->created_at->diffForHumans()); ?></small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted">No messages yet. Start the conversation!</p>
                    <?php endif; ?>
                </div>

                <!-- Message Input -->
                <form id="message-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="receiver_id" id="receiver_id" value="<?php echo e($activeUser->id); ?>">
                    <div class="input-group">
                        <input type="text" name="content" id="message-content" class="form-control" placeholder="Type a message..." required>
                        <button type="button" id="send-message" class="btn btn-primary">Send</button>
                    </div>
                </form>
                <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php else: ?>
                <p class="text-muted">Select a conversation to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Initialize Pusher
    Pusher.logToConsole = true;

    const echo = new Echo({
        broadcaster: 'pusher',
        key: '<?php echo e(config('broadcasting.connections.pusher.key')); ?>',
        cluster: '<?php echo e(config('broadcasting.connections.pusher.options.cluster')); ?>',
        forceTLS: true
    });

    <?php if($activeUser && isset($conversation)): ?>
    echo.private('conversation.<?php echo e($conversation->id); ?>')
        .listen('MessageSent', (event) => {
            const authId = <?php echo e(auth()->id()); ?>;
            const isAuthUser = event.message.sender_id === authId;
            const messageContainer = document.getElementById('chat-messages');
            messageContainer.innerHTML += `
                <div class="mb-2 ${isAuthUser ? 'text-end' : ''}">
                    <strong>${isAuthUser ? 'You' : '<?php echo e($activeUser->name); ?>'}:</strong>
                    <p>${event.message.content}</p>
                    <small class="text-muted">${new Date(event.message.created_at).toLocaleTimeString()}</small>
                </div>
            `;
            messageContainer.scrollTop = messageContainer.scrollHeight;
        });
    <?php endif; ?>

    // Handle Sending Messages
    document.getElementById('send-message').addEventListener('click', function () {
        const receiverId = document.getElementById('receiver_id').value;
        const content = document.getElementById('message-content').value;

        fetch('<?php echo e(route('messages.store')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                content: content,
            }),
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.status === 'Message Sent!') {
                document.getElementById('message-content').value = ''; // Clear the input field
            } else {
                alert('Failed to send the message.');
            }
        })
        .catch((error) => console.error('Error sending message:', error));
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/messages.blade.php ENDPATH**/ ?>