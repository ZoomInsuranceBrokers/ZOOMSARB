<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Zoom SARB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap');

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Subtle Animated Gradient Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            background: linear-gradient(120deg, rgba(30, 60, 114, 0.12), rgba(255, 179, 71, 0.10), rgba(67, 206, 162, 0.10), rgba(24, 90, 157, 0.10), rgba(248, 87, 166, 0.10), rgba(255, 88, 88, 0.10));
            background-size: 200% 200%;
            animation: gradientMove 10s ease-in-out infinite;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.3;
            z-index: 1;
            animation: floatShape 8s infinite alternate;
        }

        .shape1 {
            width: 180px;
            height: 180px;
            background: #ffb347;
            top: 5%;
            left: 8%;
            animation-delay: 0s;
        }

        .shape2 {
            width: 120px;
            height: 120px;
            background: #43cea2;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape3 {
            width: 90px;
            height: 90px;
            background: #f857a6;
            top: 60%;
            left: 10%;
            animation-delay: 4s;
        }

        .shape4 {
            width: 60px;
            height: 60px;
            background: #ff5858;
            top: 20%;
            left: 75%;
            animation-delay: 1s;
        }

        @keyframes floatShape {
            0% {
                transform: translateY(0) scale(1);
            }

            100% {
                transform: translateY(-30px) scale(1.1);
            }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.97);
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            width: 100%;
            max-width: 370px;
            overflow: hidden;
            margin: 0;
        }

        .login-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 0.5rem;
            text-align: center;
            letter-spacing: 1px;
        }

        .brand {
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffb347;
            margin-bottom: 1.5rem;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px #ffb34733;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #2a5298;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e0e7ef;
            border-radius: 8px;
            background: #f7faff;
            font-size: 1rem;
            transition: border 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ffb347;
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(90deg, #ffb347 0%, #ffb347 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px #ffb34733;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #ffb347 0%, #ff5858 100%);
            box-shadow: 0 4px 16px #ffb34733;
        }

        .alert {
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.98rem;
        }

        .alert-error {
            background: #ffe5e5;
            color: #d32f2f;
            border: 1px solid #f8bcbc;
        }

        .alert-success {
            background: #e6ffed;
            color: #388e3c;
            border: 1px solid #b2f2bb;
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            color: #2a5298;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #ffb347;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem 0.8rem 1.2rem 0.8rem;
                margin: 0;
            }

            .brand {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="animated-bg"></div>
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="shape shape3"></div>
    <div class="shape shape4"></div>
    <div class="login-container">
        <div class="login-title">Sign In</div>
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <img src="{{ asset('assets/images/rezoom.png') }}" alt="Zoom SARB Logo"
                style="width: 200px; height: 80px; object-fit: contain; max-width: 100%;">
        </div>
        {{-- Success Message --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left: 1.2em;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn-login">Login</button>
            <a href="#" class="forgot-link">Forgot Password?</a>
        </form>
    </div>
</body>

</html>
