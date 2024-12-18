<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', config('app.name', 'Laravel')); ?></title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
</head>
<body>
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="<?php echo e(route('home')); ?>">Online Marketplace</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="ml-auto navbar-nav">
                        <li class="nav-item">
                            <form class="my-2 form-inline my-lg-0" action="<?php echo e(route('search')); ?>" method="GET">
                                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query" style="border-radius: 25px; padding: 5px 10px;">
                                <button class="my-2 btn btn-outline-light my-sm-0" type="submit" style="border-radius: 25px;">Search</button>
                            </form>
                        </li>
                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?>

                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                                    <a class="dropdown-item" href="<?php echo e(route('settings')); ?>">Settings</a>
                                    <a class="dropdown-item" href="<?php echo e(route('home')); ?>">Home</a>
                                    <a class="dropdown-item" href="<?php echo e(route('about')); ?>">About</a>
                                    <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">Profile</a>
                                    <a class="dropdown-item" href="#contact">Contact</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                       Logout
                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo e(route('home')); ?>">Home</a>
                                    <a class="dropdown-item" href="<?php echo e(route('about')); ?>">About</a>
                                    <a class="dropdown-item" href="#contact">Contact</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo e(route('login')); ?>">Login</a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </header>

        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\online-marketplace\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>