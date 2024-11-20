<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
</head>
<body>
    <h1>New Message from {{ $data['name'] }}</h1>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Phone:</strong> {{ $data['phone'] }}</p>
    <p><strong>Company:</strong> {{ $data['company'] }}</p>
    <p><strong>Message:</strong></p>
    <blockquote>{{ $data['message'] }}</blockquote>
</body>
</html>
