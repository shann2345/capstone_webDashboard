<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="{{ route('login') }}" method="POST"> 
            @csrf 
            <input type="email" name="email" placeholder="Enter Email"></input> 
            <br>
            <input type="password" name="password" placeholder="Enter Password"></input> 
            <br>
            <p>Don't have an account? <a href="">Sign Up (Instructor)</a></p> 
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>