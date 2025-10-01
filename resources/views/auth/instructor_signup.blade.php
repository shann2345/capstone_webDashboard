<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sign Up | LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --secondary: #06b6d4;
            --error: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --radius: 1rem;
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 0.5rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(79, 70, 229, 0.1), rgba(139, 92, 246, 0.1));
            z-index: -1;
        }

        .container {
            display: flex;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 800px;
            width: 100%;
            max-height: 95vh;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            .container {
                flex-direction: column;
                margin: 0.5rem;
                max-height: 98vh;
            }
            .image-section {
                min-height: 150px;
            }
            body {
                padding: 0.25rem;
            }
        }

        .image-section {
            flex: 1;
            background: url('{{ asset('images/Olinlogo.png') }}') no-repeat center center;
            background-size: contain;
            position: relative;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(139, 92, 246, 0.05));
            backdrop-filter: blur(0.5px);
        }

        .logo-container {
            position: relative;
            z-index: 2;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 250px;
            width: 85%;
        }

        .logo-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 0.8rem;
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .logo-image:hover {
            transform: scale(1.05);
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            opacity: 0.8;
        }

        .card {
            background: transparent;
            padding: 0;
            box-shadow: none;
            max-width: 100%;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            text-align: center;
            letter-spacing: -0.02em;
        }

        h1 {
            margin: 0 0 0.5rem;
            font-size: 1.875rem;
            font-weight: 700;
            text-align: center;
            color: var(--text);
            letter-spacing: -0.025em;
        }

        .subtitle {
            text-align: center;
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .input-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .input-group:hover input {
            border-color: var(--primary-light);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border);
            border-radius: calc(var(--radius) - 0.25rem);
            font-size: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background-color: white;
            transform: translateY(-1px);
        }

        input::placeholder {
            color: var(--text-light);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: calc(var(--radius) - 0.25rem);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background-color: white;
            color: var(--text);
            border: 2px solid var(--border);
            padding: 1rem;
            border-radius: calc(var(--radius) - 0.25rem);
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-google::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #4285f4, #ea4335, #fbbc05, #34a853);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-google:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-google:hover::before {
            opacity: 0.1;
        }

        .btn-google span, .btn-google img {
            position: relative;
            z-index: 1;
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        .separator {
            margin: 1.5rem 0;
            text-align: center;
            position: relative;
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 500;
        }

        .separator::before,
        .separator::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        .separator::before {
            left: 0;
        }

        .separator::after {
            right: 0;
        }

        .separator span {
            background: var(--card-bg);
            padding: 0 1rem;
            position: relative;
            z-index: 1;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .links p {
            color: var(--text-light);
            margin: 0;
        }

        .links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
        }

        .links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transition: width 0.3s;
        }

        .links a:hover {
            color: var(--primary-dark);
        }

        .links a:hover::after {
            width: 100%;
        }

        .error-message {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--error);
            padding: 1rem 1.25rem;
            border-radius: calc(var(--radius) - 0.25rem);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .error-message ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .error-message li {
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <div class="card">
                <h1>Create Your Account</h1>
                <p class="subtitle">Join our learning management system and start your educational journey today.</p>

                @if ($errors->any())
                    <div class="error-message">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('instructor.register.post') }}" id="createRegisterForm">
                    @csrf

                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn-primary" id="submitRegisterButton">Sign Up</button>
                </form>

                <div class="separator"><span>OR</span></div>

                <a href="{{ route('socialite.google.redirect') }}" class="btn-google">
                    <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google logo"/>
                    Sign up with Google
                </a>

                <div class="links">
                    <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                </div>
            </div>
        </div>
        <div class="image-section">
            <div class="logo-container">
                <img src="{{ asset('images/Olinlogo.png') }}" alt="OLIN Logo" class="logo-image">
                <div class="logo-text">OLIN</div>
                <div class="logo-subtitle">Learning Management System</div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('createRegisterForm').addEventListener('submit', function() {
            const button = document.getElementById('submitRegisterButton');
            button.disabled = true;
            button.innerText = 'Processing...';
        });
    </script>
</body>
</html>
