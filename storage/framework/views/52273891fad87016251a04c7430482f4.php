<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Online Marketplace</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/indexstyle.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background: #f8f9fa;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1050;
        }

        .sidebar.active {
            right: 0;
        }

        .sidebar-header {
            padding: 10px 15px;
            background: #007bff;
            color: #fff;
        }

        .message-item {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .message-item:hover {
            background: #f1f1f1;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
            }
        }

        .input-group {
            border-radius: 25px;
            overflow: hidden;
        }

        .input-group .form-control {
            border-radius: 25px 0 0 25px;
            padding: 5px 10px;
        }

        .input-group .btn {
            border-radius: 0 25px 25px 0;
            background-color: #000;
            color: #fff;
            border: none;
        }

        .input-group .btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo e(route('home')); ?>">Online Marketplace</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <form class="my-2 form-inline my-lg-0" action="<?php echo e(route('search')); ?>" method="GET">
                                <div class="input-group" style="border-radius: 25px; overflow: hidden;">
                                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="query" style="border-radius: 25px 0 0 25px; padding: 5px 10px;">
                                    <button class="btn btn-dark" type="submit" style="border-radius: 0 25px 25px 0;">Search</button>
                                </div>
                            </form>
                        </li>
                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)" onclick="toggleSidebar()">
                                    <i class="fas fa-bell"></i>
                                    <?php if(isset($unreadNotifications) && $unreadNotifications > 0): ?>
                                        <span class="badge badge-danger"><?php echo e($unreadNotifications); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?>

                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('settings')); ?>">Settings</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('home')); ?>">Home</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('about')); ?>">About</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">Profile</a></li>
                                    <li><a class="dropdown-item" href="#contact">Contact</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                           Logout
                                        </a>
                                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo e(route('home')); ?>">Home</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('about')); ?>">About</a></li>
                                    <li><a class="dropdown-item" href="#contact">Contact</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('login')); ?>">Login</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h5>Notifications</h5>
            <button type="button" class="close" aria-label="Close" onclick="toggleSidebar()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="sidebar-content">
            <button class="btn btn-primary" onclick="viewMessages()">View Messages</button>
            <?php if(isset($messages) && $messages->isNotEmpty()): ?>
                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="message-item <?php echo e(isset($activeUser) && $activeUser->id === $message->sender->id ? 'bg-light' : ''); ?>">
                        <a href="<?php echo e(route('messages.index', ['user_id' => $message->sender->id])); ?>" class="d-flex align-items-center text-decoration-none text-dark">
                            <img src="<?php echo e(asset($message->sender->profile_picture ?? 'default-avatar.png')); ?>" 
                                alt="<?php echo e($message->sender->name); ?>" 
                                class="img-thumbnail rounded-circle" 
                                style="width: 40px; height: 40px; object-fit: cover;">
                            <span class="ms-2"><?php echo e($message->sender->name); ?></span>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <p>No notifications available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mt-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="mt-4 text-center">
        <p>&copy; <?php echo e(date('Y')); ?> Online Marketplace</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        }

        function viewMessages() {
            window.location.href = "<?php echo e(route('messages.index')); ?>";
        }

        function viewMessage(messageId) {
            $.ajax({
                url: `/messages/${messageId}`,
                type: 'GET',
                success: function(data) {
                    $('.sidebar-content').html(`
                        <h6>${data.title}</h6>
                        <p>${data.content}</p>
                    `);
                    
                },
                error: function() {
                    alert('Error fetching message content.');
                }
            });
        }
    </script>
</body>
</html>
<?php /**PATH C:\online-marketplace\resources\views/layouts/navlayout.blade.php ENDPATH**/ ?>