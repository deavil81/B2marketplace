<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Online Marketplace'))</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/indexstyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Online Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex ms-auto" action="{{ route('search') }}" method="GET" style="width: 50%;">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query" required>
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="javascript:void(0)" onclick="toggleSidebar()">
                            <i class="bi bi-bell"></i>
                            @if(auth()->check() && isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadNotifications }}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            @endif
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                                <a class="dropdown-item" href="{{ route('settings') }}">Settings</a>
                                <a class="dropdown-item" href="{{ route('home') }}">Home</a>
                                <a class="dropdown-item" href="{{ route('about') }}">About</a>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a>
                                <a class="dropdown-item" href="#contact">Contact</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar for messages -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h5>Messages</h5>
            <button type="button" class="close" aria-label="Close" onclick="toggleSidebar()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="sidebar-content">
            <!-- Display messages here -->
            @if(isset($messages) && $messages->isNotEmpty())
                @foreach ($messages as $message)
                    <div class="message-item" onclick="viewMessage('{{ $message->id }}')">
                        <h6>{{ $message->title }}</h6>
                        <p>{{ Str::limit($message->content, 100) }}</p>
                    </div>
                @endforeach
            @else
                <p>No messages available.</p>
            @endif
        </div>
    </div>

    <main class="py-4">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");

            if (sidebar.classList.contains("active")) {
                sidebar.style.right = "0";
            } else {
                sidebar.style.right = "-300px";
            }
        }

        function viewMessage(messageId) {
            $.ajax({
                url: `/messages/${messageId}`,
                type: 'GET',
                success: function(data) {
                    var messageContent = `
                        <h6>${data.title}</h6>
                        <p>${data.content}</p>
                    `;
                    $('.sidebar-content').html(messageContent);
                    toggleSidebar();
                },
                error: function() {
                    alert('Error fetching message content.');
                }
            });
        }
    </script>
</body>
</html>
