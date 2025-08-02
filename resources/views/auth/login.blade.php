<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: #f6f5f7;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: 'Montserrat', sans-serif;
        height: 100vh;
        margin: -20px 0 50px;
    }

    .container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 480px;
        margin: 40px auto;
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
        opacity: 1;
        transform: translateX(0);
    }

    .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
        transform: translateX(0);
    }

    .container.right-panel-active .sign-in-container {
        transform: translateX(100%);
    }

    .container.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: show 0.6s;
    }

    @keyframes show {

        0%,
        49.99% {
            opacity: 0;
            z-index: 1;
        }

        50%,
        100% {
            opacity: 1;
            z-index: 5;
        }
    }

    form {
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }

    input {
        background-color: #eee;
        border: none;
        padding: 12px 15px;
        margin: 8px 0;
        width: 100%;
    }

    .input-icon {
        display: flex;
        align-items: center;
        width: 100%;
        margin: 8px 0;
        background: #eee;
        border-radius: 4px;
        padding-left: 4px;
    }

    .input-icon i {
        color: #39786c;
        width: 20px;
        height: 20px;
        margin-right: 8px;
    }

    .input-icon input {
        background: transparent;
        border: none;
        outline: none;
        width: 100%;
        margin: 0;
        padding: 12px 15px 12px 8px;
        font-size: 1rem;
    }

    button {
        border-radius: 20px;
        border: 1px solid #39786c;
        background-color: #39786c;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: transform 80ms ease-in;
        margin-top: 10px;
    }

    button:active {
        transform: scale(0.95);
    }

    button:focus {
        outline: none;
    }

    .ghost {
        background-color: transparent;
        border-color: #fff;
    }

    .social-container {
        margin: 20px 0;
    }

    .social-container a {
        border: 1px solid #ddd;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin: 0 5px;
        height: 40px;
        width: 40px;
    }

    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
    }

    .container.right-panel-active .overlay-container {
        transform: translateX(-100%);
    }

    .overlay {
        background: linear-gradient(to right, #39786c, #39786c);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #fff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .container.right-panel-active .overlay {
        transform: translateX(50%);
    }

    .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .overlay-left {
        transform: translateX(-20%);
    }

    .container.right-panel-active .overlay-left {
        transform: translateX(0);
    }

    .overlay-right {
        right: 0;
        transform: translateX(0);
    }

    .container.right-panel-active .overlay-right {
        transform: translateX(20%);
    }

    .title {
        font-weight: bold;
        margin: 0;
    }

    .text {
        font-size: 14px;
        font-weight: 100;
        line-height: 20px;
        letter-spacing: 0.5px;
        margin: 20px 0 30px;
    }

    @media (max-width: 600px) {

        html,
        body {
            width: 100vw;
            height: 100vh;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .container {
            width: 100vw;
            height: 100vh;
            min-width: unset;
            min-height: unset;
            margin: 0;
            border-radius: 0;
            box-shadow: none;
            position: relative;
            max-width: 100vw;
            max-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            position: absolute;
            width: 100vw !important;
            height: 100vh !important;
            top: 0 !important;
            transition: all 0.6s cubic-bezier(.77, 0, .18, 1);
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            padding: 0 10px;
            height: auto;
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .title {
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }

        button,
        input {
            font-size: 1rem;
        }

        .signup-toggle-btn,
        .back-to-signin-btn {
            display: block;
            width: 100%;
            margin: 1.5rem 0 0 0;
            background: #39786c;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 12px 0;
            font-weight: bold;
            font-size: 1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-align: center;
        }

        .overlay-container,
        .overlay,
        .overlay-panel {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            position: absolute !important;
            left: -9999px !important;
        }

        .sign-in-container {
            left: 0;
            width: 100vw;
            z-index: 2;
            opacity: 1;
            transform: translateX(0);
        }

        .sign-up-container {
            left: 100vw;
            width: 100vw;
            z-index: 1;
            opacity: 1;
            transform: translateX(0);
        }

        .container.right-panel-active .sign-in-container {
            left: 0;
            transform: translateX(-100vw);
        }

        .container.right-panel-active .sign-up-container {
            left: 0;
            transform: translateX(0);
            z-index: 5;
        }
    }
</style>
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf
            <h1 class="title">Create Account</h1>
            
            <div class="input-icon"><i data-feather="user"></i><input type="text" name="username"
                    placeholder="Username" value="{{ old('username') }}" required /></div>
            <span class="text-danger small error-username"></span>
            <div class="input-icon"><i data-feather="user"></i><input type="text" name="namalengkap"
                    placeholder="Nama Lengkap" value="{{ old('namalengkap') }}" required /></div>
            <span class="text-danger small error-namalengkap"></span>
            <div class="input-icon"><i data-feather="lock"></i><input type="password" name="password"
                    placeholder="Password" required /></div>
            <span class="text-danger small error-password"></span>
            <div class="input-icon"><i data-feather="lock"></i><input type="password" name="password_confirmation"
                    placeholder="Confirm Password" required /></div>
            <span class="text-danger small error-password_confirmation"></span>
            <div id="register-errors"></div>
            <button type="submit">Sign Up</button>
            <button type="button" class="back-to-signin-btn" style="display:none;">Back to Sign In</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        @if (session('success'))
            <div class="alert alert-success" style="width:100%;text-align:center;">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1 class="title">Sign in</h1>
            
            <div class="input-icon"><i data-feather="user"></i><input type="text" name="username"
                    placeholder="Username" value="{{ old('username') }}" required /></div>
            @error('username')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
            <div class="input-icon"><i data-feather="lock"></i><input type="password" name="password"
                    placeholder="Password" required /></div>
            @error('password')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
            <a href="#">Forgot your password?</a>
            <button type="submit">Sign In</button>
            <button type="button" class="signup-toggle-btn" style="display:none;">Sign Up</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1 class="title">Welcome Back!</h1>
                <p class="text">To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1 class="title">Hello, Friend!</h1>
                <p class="text">Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>
<script>
    function isMobile() {
        return window.innerWidth <= 600;
    }

    function slideToRegister() {
        document.getElementById('container').classList.add('right-panel-active');
    }

    function slideToLogin() {
        document.getElementById('container').classList.remove('right-panel-active');
    }
    // Event untuk tombol Sign Up dan Back di mobile
    function attachMobileToggleEvents() {
        document.querySelectorAll('.signup-toggle-btn').forEach(function(btn) {
            btn.onclick = slideToRegister;
        });
        document.querySelectorAll('.back-to-signin-btn').forEach(function(btn) {
            btn.onclick = slideToLogin;
        });
    }
    attachMobileToggleEvents();
    document.getElementById('signUp').addEventListener('click', function() {
        slideToRegister();
    });
    document.getElementById('signIn').addEventListener('click', function() {
        slideToLogin();
    });
    // Mobile only: toggle button
    function updateMobileButtons() {
        document.querySelectorAll('.signup-toggle-btn').forEach(function(btn) {
            btn.style.display = isMobile() ? 'block' : 'none';
        });
        document.querySelectorAll('.back-to-signin-btn').forEach(function(btn) {
            btn.style.display = isMobile() ? 'block' : 'none';
        });
    }
    updateMobileButtons();
    window.addEventListener('resize', function() {
        updateMobileButtons();
        attachMobileToggleEvents();
    });
    // AJAX register
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        // Bersihkan error lama
        form.querySelectorAll('.text-danger.small').forEach(function(el) {
            el.innerHTML = ''
        });
        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: data
            })
            .then(response => response.json())
            .then(json => {
                if (json.success) {
                    let container = document.getElementById('container');
                    slideToLogin();
                    document.getElementById('register-errors').innerHTML = '';
                    let loginForm = document.querySelector('.sign-in-container form');
                    let alert = loginForm.querySelector('.alert-success');
                    if (!alert) {
                        alert = document.createElement('div');
                        alert.className = 'alert alert-success';
                        alert.style.width = '100%';
                        alert.style.textAlign = 'center';
                        loginForm.prepend(alert);
                    }
                    alert.innerText = json.message;
                    form.reset();
                } else if (json.errors) {
                    // Tampilkan error di bawah input terkait
                    Object.entries(json.errors).forEach(function([key, msgArr]) {
                        var el = form.querySelector('.error-' + key);
                        if (el) el.innerHTML = msgArr[0];
                    });
                }
            });
    });
    @if (session('registered'))
        document.addEventListener('DOMContentLoaded', function() {
            slideToLogin();
        });
    @endif
    document.addEventListener('DOMContentLoaded', function() {
        if (window.feather) feather.replace();
    });
</script>
