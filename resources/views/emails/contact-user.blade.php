<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
</head>
<body>
    <h1>Thank You, {{ $data['name'] }}</h1>
    <p>We have received your message:</p>
    <blockquote>{{ $data['message'] }}</blockquote>
    <p>We will get back to you soon!</p>
</body>
</html>
