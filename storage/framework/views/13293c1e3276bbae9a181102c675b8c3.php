

<?php $__env->startSection('title', 'Messaging'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4 d-flex">
    <!-- Sidebar for Users List -->
    <div class="col-md-3 border-end">
        <h5 class="p-2">Chats</h5>
        <ul class="list-group" style="max-height: 500px; overflow-y: auto;">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('messages.index', ['user_id' => $user->id])); ?>" 
                class="list-group-item list-group-item-action d-flex align-items-center <?php echo e($activeUser && $activeUser->id === $user->id ? 'active' : ''); ?>"
                style="border-radius: 5px;">
                    <!-- User Profile Photo -->
                    <img 
                        src="<?php echo e($user && $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png')); ?>" 
                        alt="Profile Picture" 
                        class="img-thumbnail rounded-circle" 
                        style="width: 40px; height: 40px; object-fit: cover;" 
                        onerror="this.src='<?php echo e(asset('images/default-profile.png')); ?>';"
                    >

                    <!-- User Name and Latest Message -->
                    <div>
                        <strong><?php echo e($user->name); ?></strong>
                        <br>
                        <small class="text-muted">
                            <?php echo e(optional($user->latestMessage)->content ?? 'No messages yet.'); ?>

                        </small>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="p-2 text-muted">No conversations yet. Start a new one!</p>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><?php echo e($activeUser ? $activeUser->name : 'Select a chat'); ?></strong>
            </div>
            <div class="card-body chat-area" style="height: 400px; overflow-y: auto; background-color: #f8f9fa; padding: 10px;">
                <?php if($messages && $messages->count()): ?>
                    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex <?php echo e($message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start'); ?> mb-2">
                            <div class="<?php echo e($message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark'); ?> p-3 rounded shadow-sm" 
                                 style="max-width: 60%; word-wrap: break-word; border-radius: 15px;">
                                <p class="mb-1"><?php echo e($message->content); ?></p>
                                <small class="text-muted d-block <?php echo e($message->sender_id === auth()->id() ? 'text-end' : ''); ?>">
                                    <?php echo e($message->created_at->diffForHumans()); ?>

                                </small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-center text-muted">No messages yet. Start the conversation!</p>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <form id="message-form" class="d-flex">
                    <?php echo csrf_field(); ?>
                    <input type="text" id="message-input" placeholder="Type a message..." class="form-control me-2" required 
                           style="border-radius: 20px; padding: 10px;">
                    <input type="hidden" id="receiver_id" value="<?php echo e($activeUser ? $activeUser->id : ''); ?>">
                    <button type="button" id="send-message" class="btn btn-primary" 
                            style="border-radius: 20px; padding: 8px 20px;">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const sendButton = document.getElementById('send-message');
    const messageInput = document.getElementById('message-input');
    const messageContainer = document.querySelector('.chat-area');

    sendButton.onclick = function () {
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
            messageContainer.innerHTML += `
                <div class="mb-2 d-flex justify-content-end">
                    <div class="p-3 text-white rounded shadow-sm bg-primary" style="max-width: 60%; word-wrap: break-word; border-radius: 15px;">
                        <p class="mb-1">${response.data.message.content}</p>
                        <small class="text-muted d-block text-end">Just now</small>
                    </div>
                </div>
            `;
            messageInput.value = '';
            messageContainer.scrollTop = messageContainer.scrollHeight;
        })
        .catch((error) => {
            console.error('Error sending message:', error);
        });
    };

    // Scroll to the latest message on page load
    messageContainer.scrollTop = messageContainer.scrollHeight;
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.navlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\online-marketplace\resources\views/messages/messages.blade.php ENDPATH**/ ?>