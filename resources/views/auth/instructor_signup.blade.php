<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Sign Up</title>
    </head>
<body>
    <h1>Register as an Instructor</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('instructor.register.post') }}" id="createRegisterForm">
        @csrf

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="password_confirmation">Confirm Password:</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>

        <button type="submit" 
                id="submitRegisterButton">Sign Up</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
</body>
    <script>
        document.getElementById('createRegisterForm').addEventListener('submit', function() {
            // Disable the submit button to prevent multiple submissions
            document.getElementById('submitRegisterButton').setAttribute('disabled', 'disabled');
            // Optionally, change the text or add a loading spinner
            document.getElementById('submitRegisterButton').innerText = 'Processing...';
        });
    </script>
</html>