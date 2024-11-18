<!DOCTYPE html>
<html>
<head>
    <title>Account Created</title>
</head>
<body>
    <h1>Hello, {{ $userName }}</h1>
    <p>Your account has been created successfully. Below are your login details:</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Please log in and change your password for security purposes.</p>
    <p>Thank you!</p>
</body>
</html>
