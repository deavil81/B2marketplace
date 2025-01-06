<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($category->name); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/categorystyle.css')); ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">Online Marketplace</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="ml-auto navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('about')); ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <div class="text-center category-container">
            <h1><?php echo e($category->name); ?></h1>
            <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>">
            <p>More details about this category will be displayed here.</p>
        </div>

        <h2 class="mt-4">Products in <?php echo e($category->name); ?></h2>
        <?php if($products->isNotEmpty()): ?>
            <div class="row">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-4 col-md-4">
                        <div class="card h-100">
                            <?php if($product->images && $product->images->isNotEmpty()): ?>
                                <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(asset('default-product.png')); ?>" class="card-img-top" alt="<?php echo e($product->title); ?>">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo e($product->title); ?></h5>
                                <p class="card-text"><?php echo e(Str::limit($product->description, 100)); ?></p>
                                <a href="<?php echo e(route('products.show', $product->id)); ?>" class="mt-auto btn btn-primary">View Product</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <p class="text-center">No products available in this category.</p>
        <?php endif; ?>
    </main>

    <footer class="mt-4 text-center footer">
        <p>&copy; <?php echo e(date('Y')); ?> Online Marketplace</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\online-marketplace\resources\views/categories/show.blade.php ENDPATH**/ ?>