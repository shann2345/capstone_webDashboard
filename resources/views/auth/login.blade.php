<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #2d89ef;
            --primary-dark: #1b66c9;
            --error: #e74c3c;
            --background: #f9fafb;
            --text: #333;
            --radius: 0.75rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .card {
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--radius);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            max-width: 420px;
            width: 100%;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        h1 {
            margin: 0 0 2rem;
            font-size: 1.75rem;
            font-weight: 600;
            text-align: center;
            color: #2c3e50;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #fff;
            width: 100%;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            background-color: #fff;
            color: #555;
            border: 1px solid #ccc;
            padding: 0.8rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 1rem;
        }

        .btn-google:hover {
            background-color: #f1f1f1;
            transform: translateY(-1px);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        .separator {
            margin: 2rem 0;
            text-align: center;
            position: relative;
            font-size: 0.85rem;
            color: #aaa;
        }

        .separator::before,
        .separator::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #ddd;
        }

        .separator::before {
            left: 0;
        }

        .separator::after {
            right: 0;
        }

        .separator span {
            background: #fff;
            padding: 0 0.5rem;
            position: relative;
            z-index: 1;
        }

        .links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .links a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .links a:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }

        .error-message {
            background-color: #ffe6e6;
            color: var(--error);
            border: 1px solid var(--error);
            padding: 1rem;
            border-radius: var(--radius);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .error-message ul {
            margin: 0;
            padding-left: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">LMS</div>
        <h1>Welcome Back</h1>

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

        <a href="{{ route('socialite.google.redirect') }}" class="btn-google">
            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google logo" />
            Sign in with Google
        </a>

        <div class="links">
            <p>Don't have an account? <a href="{{ route('instructor.register.get') }}">Create one</a></p>
        </div>
    </div>
</body>
</html>
