{{-- resources/views/auth/verify-email.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: start;
            min-height: 100vh;
            padding-top: 50px;
        }
        .container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 22px;
            text-align: center;
        }
        p {
            font-size: 14px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .extra-actions {
            margin-top: 15px;
        }
        .extra-actions form {
            margin-top: 10px;
        }
    </style>
</head>
<body>
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
                <button type="submit">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background-color: #dc3545;">Log Out</button>
            </form>
        </div>
    </div>
</body>
</html>
