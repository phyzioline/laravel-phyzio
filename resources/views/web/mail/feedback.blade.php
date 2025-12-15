<!DOCTYPE html>
<html>
<head>
    <title>New Feedback</title>
</head>
<body>
    <h2>New Feedback Recieved</h2>
    <p><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</p>
    <p><strong>Subject:</strong> {{ $data['subject'] }}</p>
    <br>
    <p><strong>Message:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>
