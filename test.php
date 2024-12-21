<?php

require __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\ProductController;

try {
    $controller = new ProductController();
    echo 'ProductController loaded successfully.';
} catch (\Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}
