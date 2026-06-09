<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nooise - Register</title>
    <link rel="stylesheet" href="{{asset('cssnooise/styleregister.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container">
        <header>
            <div class="logo">
                <img src="{{ asset('nooisefoto/nooise.png') }}" alt="Nooise Logo" style="max-height: 85px; width: auto; object-fit: contain; display: block; margin: 0 auto;">
            </div>
        </header>

        <div class="gambarutama">
            <img src="all foto/b8a3b344b345c74b433e5160aa0fcf29 1.png" alt="background">
        </div>

        <div class="gambarbayang">
            <img src="all foto/Rectangle 9.png" alt="background bayang">
        </div>

        <main class="register-card">
            <h2>Register</h2>
            <p class="login-link">Already have an account? <a href="{{ route('halaman.login') }}">Login here</a></p>

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="input-group">
                    <input type="text" name="name" placeholder="Full Name" required value="{{ old('name') }}">
                </div>

                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                </div>

                <div class="input-row">
                    <div class="country-code">
                        <select name="country_code" style="font-style: normal; cursor: pointer;">
                            <option value="+62" {{ old('country_code') == '+62' ? 'selected' : '' }}>🇮🇩 +62 (ID)</option>
                            <option value="+60" {{ old('country_code') == '+60' ? 'selected' : '' }}>🇲🇾 +60 (MY)</option>
                            <option value="+65" {{ old('country_code') == '+65' ? 'selected' : '' }}>🇸🇬 +65 (SG)</option>
                            <option value="+66" {{ old('country_code') == '+66' ? 'selected' : '' }}>🇹🇭 +66 (TH)</option>
                            <option value="+63" {{ old('country_code') == '+63' ? 'selected' : '' }}>🇵🇭 +63 (PH)</option>
                            <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1 (US)</option>
                            <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                            <option value="+61" {{ old('country_code') == '+61' ? 'selected' : '' }}>🇦🇺 +61 (AU)</option>
                            <option value="+81" {{ old('country_code') == '+81' ? 'selected' : '' }}>🇯🇵 +81 (JP)</option>
                            <option value="+82" {{ old('country_code') == '+82' ? 'selected' : '' }}>🇰🇷 +82 (KR)</option>
                        </select>
                    </div>
                    <div class="phone-number">
                        <input type="tel" name="phone_number" placeholder="Phone Number" required value="{{ old('phone_number') }}">
                    </div>
                </div>

                <div class="input-group password-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fa-regular fa-eye-slash" id="togglePasswordBtn"></i>
                </div>

                <div class="input-group password-group">
                    <input type="password" name="password_confirmation" id="passwordConfirm" placeholder="Confirm Password" required>
                    <i class="fa-regular fa-eye-slash" id="togglePasswordConfirmBtn"></i>
                </div>

                <p class="terms">
                    By registering, I agree to Nooise <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                </p>

                <button type="submit" class="btn-register">Register</button>
            </form>
        </main>
    </div>

    <script>
        function setupToggle(btnId, inputId) {
            const toggleBtn = document.getElementById(btnId);
            const inputField = document.getElementById(inputId);

            toggleBtn.addEventListener('click', function () {
                const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                inputField.setAttribute('type', type);

                if (type === 'password') {
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }

        setupToggle('togglePasswordBtn', 'password');
        setupToggle('togglePasswordConfirmBtn', 'passwordConfirm');
    </script>
</body>
</html>

