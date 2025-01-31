<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/loginstyle.css') }}">
    <meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">
</head>
<body class="login-page">
    <div class="container-login100">
        <div class="wrap-login100">
            <!-- Login Form -->
            <form class="login100-form validate-form" id="login-form" action="{{ route('login.post') }}" method="POST">
                @csrf
                <span class="login100-form-title">Login</span>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-login">Login</button>
                <div class="mt-3 text-center form-group">
                    <a class="btn btn-link" href="{{ route('password.request') }}">Forgot Your Password?</a>
                </div>
                <div id="my-signin2" class="google-signin-button"></div>
            </form>

            <!-- Toggle Links -->
            <div class="form-toggle">
                <a href="#" id="show-login" class="active">Login</a> | 
                <a href="#" id="show-register">Sign Up</a>
            </div>

            <!-- Registration Form -->
            <form class="login100-form validate-form" id="register-form" action="{{ route('register.details') }}" method="GET" style="display: none;">
                @csrf
                <span class="login100-form-title">Register</span>
                <div class="form-group">
                    <label for="email_register">Email</label>
                    <input type="email" class="form-control" id="email_register" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password_register">Password</label>
                    <input type="password" class="form-control" id="password_register" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="buyer">Buyer</option>
                        <option value="seller">Seller</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-register">Proceed</button>
            </form>
        </div>

    <script>
        function onSuccess(googleUser) {
            console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
        }
        function onFailure(error) {
            console.log(error);
        }
        function renderButton() {
            gapi.signin2.render('my-signin2', {
                'scope': 'profile email',
                'width': 240,
                'height': 50,
                'longtitle': true,
                'theme': 'dark',
                'onsuccess': onSuccess,
                'onfailure': onFailure
            });
        }

        // Toggle between login and registration forms
        document.getElementById('show-register').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
        });

        document.getElementById('show-login').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        });
    </script>
    <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
</body>
</html>
