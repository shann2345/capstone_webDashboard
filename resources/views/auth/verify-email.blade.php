{{-- resources/views/auth/verify-email.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <h1>Verify Your Email Address</h1>

    <p>Thanks for signing up! Before getting started, please enter the 6-digit verification code sent to your email address. If you didn't receive the email, we will gladly send you another.</p>

    @if (session('status'))
        <div style="color: green;">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify.code') }}">
        @csrf
        <label for="verification_code">Verification Code:</label><br>
        <input type="text" id="verification_code" name="verification_code" required autofocus><br><br>
        <button type="submit">Verify Email</button>
    </form>

    <form method="POST" action="{{ route('verification.send') }}" style="margin-top: 10px;">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 10px;">
        @csrf
        <button type="submit">Log Out</button>
    </form>
</body>
</html>