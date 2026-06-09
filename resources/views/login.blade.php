<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nooise</title>
    <link rel="stylesheet" href="{{asset('cssnooise/stylelogin.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="background-container">
        <div class="logo-top">
            <img src="{{ asset('nooisefoto/nooise.png') }}" alt="Nooise Logo" style="max-height: 85px; width: auto; object-fit: contain; display: block; margin: 0 auto;">
        </div>
        <div class="gambarutama">
            <img src="{{ asset('nooisefoto/background.png') }}" alt="background">
        </div>
        <div class="gambarbayangan"></div>

        <div class="login-card">
            <h2>Login</h2>
            <p class="signup-text">Don't have an account? <a href="{{ route('halaman.register') }}">Sign up here</a></p>
            
            
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-group" style="position: relative;">
                    <input type="password" name="password" id="passwordInput" placeholder="Password" required style="padding-right: 50px;">
                    <i class="fa-regular fa-eye-slash" id="togglePassword" style="position: absolute; right: 25px; top: 50%; transform: translateY(-50%); color: #63422d; cursor: pointer;"></i>
                </div>


                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox"> <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot password</a>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        togglePassword.addEventListener('click', function () {
            // toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // toggle the eye icon
            if (type === 'password') {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>