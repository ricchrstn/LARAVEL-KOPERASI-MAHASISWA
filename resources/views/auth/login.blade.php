<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login | Koperasi Mahasiswa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Your existing CSS styles here */
    </style>
</head>
<body>
    <!-- Logo -->
    <div class="logo-wrap">
        <img src="{{ asset('images/logokopma.png') }}" alt="Logo">
    </div>

    <!-- Heading -->
    <h2>Welcome Back</h2>
    <p class="subtitle">Sign in to your koperasi account</p>

    <!-- Card -->
    <div class="card">
        <div id="error" class="error"></div>

        <form id="login-form">
            @csrf
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <div class="checkbox-row">
                <label style="display: flex; align-items: center; font-size: 14px; color: #333;">
                    <input type="checkbox" name="remember" id="remember" style="margin-right: 8px; accent-color: #059669;">
                    Remember Me
                </label>
            </div>

            <button type="submit" id="submit-btn">SIGN IN</button>
        </form>
    </div>

    <!-- Links -->
    <div class="links">
        <a href="{{ url('/kopma/register') }}">Sign Up</a>|
        <a href="{{ url('/kopma/forgot-password') }}">Forgot Password?</a>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-btn');
            const errorBox = document.getElementById('error');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Loading...';
            errorBox.textContent = '';

            try {
                const response = await fetch('/kopma/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        remember: document.getElementById('remember').checked
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    errorBox.textContent = data.message || 'An error occurred';
                }
            } catch (error) {
                errorBox.textContent = 'An error occurred. Please try again.';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'SIGN IN';
            }
        });
    </script>
</body>
</html>