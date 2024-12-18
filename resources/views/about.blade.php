@extends('layouts.navlayout')

@section('title', 'About Us')

@section('content')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('home') }}" class="list-group-item list-group-item-action">Home</a>
                <a href="{{ route('about') }}" class="list-group-item list-group-item-action active">About Us</a>
                <a href="#contact" class="list-group-item list-group-item-action">Contact</a>
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="{{ route('settings') }}" class="list-group-item list-group-item-action">Settings</a>
                <a href="{{ route('logout') }}" 
                   class="list-group-item list-group-item-action"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <div class="text-center mb-4">
                <h1>About Online Marketplace</h1>
                <p>Discover the platform connecting buyers and sellers from all over the world.</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h2>Our Mission</h2>
                    <p>Our mission is to provide a seamless, reliable, and enjoyable shopping experience for our users. We strive to create a marketplace where everyone can find what they need, at competitive prices, and with the convenience of modern technology.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <img src="{{ asset('images/mission.jpg') }}" class="img-fluid rounded" alt="Our Mission">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <img src="{{ asset('images/features.jpg') }}" class="img-fluid rounded" alt="Features">
                </div>
                <div class="col-md-6 mb-4">
                    <h2>Features</h2>
                    <ul>
                        <li>Wide variety of products: From electronics to fashion, home goods to beauty products, we have it all.</li>
                        <li>User-friendly interface: Our platform is designed to be easy to navigate, ensuring a smooth shopping experience.</li>
                        <li>Secure transactions: We prioritize the security of your transactions and personal information.</li>
                        <li>Customer support: Our dedicated customer support team is here to help you with any queries or issues.</li>
                        <li>Personalized recommendations: Get product suggestions tailored to your preferences.</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h2>Why Choose Us?</h2>
                    <p>Choosing Online Marketplace means choosing quality, convenience, and a community-driven platform. We are committed to providing the best products and services to our users. Whether you are a buyer or a seller, we are here to support you every step of the way.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <img src="{{ asset('images/why-choose-us.jpg') }}" class="img-fluid rounded" alt="Why Choose Us">
                </div>
            </div>

            <div class="text-center mt-4">
                <h2>Contact Us</h2>
                <p>If you have any questions, comments, or feedback, please feel free to reach out to us through our <a href="#contact">Contact</a> page. We value your input and look forward to hearing from you.</p>
            </div>
        </div>
    </div>
</div>
@endsection
