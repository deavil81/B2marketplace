

<?php $__env->startSection('title', 'Messaging'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4 d-flex">

    <!-- Sidebar for Users List -->
    <div class="col-md-3 border-end">
        <h5 class="p-2">Chats</h5>
        <ul class="list-group">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo e($activeUser && $activeUser->id === $user->id ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('messages.index', ['user_id' => $user->id])); ?>" class="text-decoration-none text-dark">
                        <div>
                            <strong><?php echo e($user->name); ?></strong>
                            <br>
                            <small class="text-muted">
                                
                                <?php echo e(optional($user->latestMessage)->content ?? 'No messages yet.'); ?>

                            </small>
                        </div>
                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted">No conversations yet. Start a new one!</p>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="col-md-9">
        <div class="p-3 border chat-area" style="height: 500px; overflow-y: scroll;">
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
        <form id="message-form" class="mt-3">
            <input type="text" id="message-input" placeholder="Type a message..." class="form-control">
            <input type="hidden" id="receiver_id" value="<?php echo e($activeUser ? $activeUser->id : ''); ?>">
            <button type="button" id="send-message" class="btn btn-primary mt-2">Send</button>
        </form>
    </div>

</div>

<!-- Include Axios Library -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const sendButton = document.getElementById('send-message');
    sendButton.onclick = function () {
        const messageInput = document.getElementById('message-input');
        const content = messageInput.value.trim();
        const receiverId = document.getElementById('receiver_id').value;

        if (content === '') {
            alert('Please enter a message before sending.');
            return;
        }

        axios.post('<?php echo e(route('messages.store')); ?>', {
            receiver_id: receiverId,
            content: content,
        })
        .then((response) => {
            const messageContainer = document.querySelector('.chat-area');
            messageContainer.innerHTML += `
                <div class="mb-2 text-end">
                    <strong>You:</strong>
                    <p>${response.data.message.content}</p>
                    <small class="text-muted">${response.data.message.time}</small>
                </div>
            `;
            messageInput.value = '';
            messageContainer.scrollTop = messageContainer.scrollHeight;
        })
        .catch((error) => {
            console.error('Error sending message:', error);
        });
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/messages.blade.php ENDPATH**/ ?>