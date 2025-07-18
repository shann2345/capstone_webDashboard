<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Your LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 2.2em;
            font-weight: 700;
        }
        .logo {
            margin-bottom: 30px;
            font-size: 2.5em;
            color: #3498db;
            font-weight: bold;
        }
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
            padding: 14px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
            font-weight: 600;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn-google {
            background-color: #dd4b39; /* Google Red */
            color: white;
            padding: 14px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
            font-weight: 600;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-google:hover {
            background-color: #c23321;
            transform: translateY(-2px);
        }
        .btn-google img {
            margin-right: 10px;
            width: 20px;
            height: 20px;
        }
        .separator {
            margin: 30px 0;
            position: relative;
            text-align: center;
            color: #aaa;
        }
        .separator::before,
        .separator::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #eee;
        }
        .separator::before {
            left: 0;
        }
        .separator::after {
            right: 0;
        }
        .separator span {
            background-color: #fff;
            padding: 0 10px;
            position: relative;
            z-index: 1;
        }
        .links {
            margin-top: 25px;
            font-size: 0.95em;
        }
        .links a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }
        .links a:hover {
            text-decoration: underline;
            color: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            background-color: #fdeded;
            border: 1px solid #e74c3c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 0.9em;
        }
        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">LMS</div>
        <h1>Welcome Back!</h1>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <div class="separator"><span>OR</span></div>

        <!-- Google Sign-In Button (for web-based flow) -->
        <a href="{{ route('socialite.google.redirect') }}" class="btn-google">
            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google logo"/>
            Sign in with Google
        </a>

        <div class="links">
            <p>Don't have an account? <a href="{{ route('instructor.register.get') }}">Sign Up here</a></p>
            {{-- You can add a "Forgot Password?" link here later if needed --}}
        </div>
    </div>
</body>
</html>
