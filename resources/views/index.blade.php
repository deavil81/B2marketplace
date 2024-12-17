<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Marketplace</title>
    <link rel="stylesheet" href="{{ asset('css/indexstyle.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('home') }}">Online Marketplace</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="ml-auto navbar-nav">
                    <li class="nav-item">
                        <form class="my-2 form-inline my-lg-0" action="{{ route('search') }}" method="GET">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query" style="border-radius: 25px; padding: 5px 10px;">
                            <button class="my-2 btn btn-outline-light my-sm-0" type="submit" style="border-radius: 25px;">Search</button>
                        </form>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                                <a class="dropdown-item" href="{{ route('settings') }}">Settings</a>
                                <a class="dropdown-item" href="{{ route('home') }}">Home</a>
                                <a class="dropdown-item" href="{{ route('about') }}">About</a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                                <a class="dropdown-item" href="#contact">Contact</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('home') }}">Home</a>
                                <a class="dropdown-item" href="{{ route('about') }}">About</a>
                                <a class="dropdown-item" href="#contact">Contact</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                            </div>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <div class="text-center jumbotron">
            <h1 class="display-4">Welcome to the Online Marketplace</h1>
            <p class="lead">Find the best products and services just for you!</p>
        </div>

        <h2>Products You May Like</h2>
        @if($suggestedProducts->isNotEmpty())
            <div class="row">
                @foreach ($suggestedProducts as $product)
                    <div class="mb-4 col-md-3">
                        <div class="card h-100">
                            @if ($product->thumbnail && Storage::exists('public/' . $product->thumbnail->image_path))
                                <img src="{{ asset('storage/' . $product->thumbnail->image_path) }}" class="card-img-top" alt="{{ $product->title }}">
                            @elseif ($product->images && $product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->title }}">
                            @else
                                <img src="{{ asset('default-product.png') }}" class="card-img-top" alt="Default Product Image">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->title }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                <a href="{{ route('products.show', $product->id) }}" class="mt-auto btn btn-primary">View Product</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No product suggestions available.</p>
        @endif

        <h2 class="mt-4">Search By Categories</h2>
        @if($categories->isNotEmpty())
            <div class="row">
                @foreach ($categories as $category)
                    <div class="mb-4 col-md-3">
                        <div class="card h-100">
                            <div class="text-center card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-success">Explore {{ $category->name }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No categories available.</p>
        @endif
    </main>

    <footer class="mt-4 text-center footer">
        <p>&copy; {{ date('Y') }} Online Marketplace</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
