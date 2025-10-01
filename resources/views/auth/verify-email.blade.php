{{-- resources/views/auth/verify-email.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | LMS</title>
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
            padding: 1rem;
            position: relative;
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

        .container-wrapper {
            display: flex;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 800px;
            width: 100%;
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

        .container {
            flex: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .image-section {
            flex: 1;
            background: url('{{ asset('images/Olinlogo.png') }}') no-repeat center center;
            background-size: contain;
            position: relative;
            min-height: 320px;
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
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        p {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            color: var(--text-light);
            line-height: 1.6;
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

        input[type="text"] {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border);
            border-radius: calc(var(--radius) - 0.25rem);
            font-size: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            margin-bottom: 1.5rem;
            text-align: center;
            letter-spacing: 0.2em;
            font-weight: 600;
        }

        input[type="text"]:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background-color: white;
            transform: translateY(-1px);
        }

        button {
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
            margin-bottom: 1rem;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary), #0891b2);
        }

        .btn-secondary:hover {
            box-shadow: 0 10px 25px rgba(6, 182, 212, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--error), #dc2626);
        }

        .btn-danger:hover {
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }

        .message {
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            border-radius: calc(var(--radius) - 0.25rem);
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
        }

        .success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-left: 4px solid var(--success);
        }

        .error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--error);
        }

        .error ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .error li {
            margin-bottom: 0.25rem;
        }

        .extra-actions {
            margin-top: 1.5rem;
        }

        .extra-actions form {
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem;
            }
            .container-wrapper {
                flex-direction: column;
                margin: 1rem;
            }
            .image-section {
                min-height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="container">
            <h1>Verify Your Email</h1>

            <p>Please enter the 6-digit code sent to your email. If you didn’t receive it, you can request a new one below.</p>

            @if (session('status'))
                <div class="message success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="message error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify.code') }}">
                @csrf
                <label for="verification_code">Verification Code</label>
                <input type="text" id="verification_code" name="verification_code" required autofocus>
                <button type="submit">Verify Email</button>
            </form>

            <div class="extra-actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">Resend Verification Email</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-danger">Log Out</button>
                </form>
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
</body>
</html>
