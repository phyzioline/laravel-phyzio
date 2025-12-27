<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Phyzioline</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 30px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            text-align: center;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #888888;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Phyzioline!</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Thank you for registering your company account with Phyzioline. Your account has been created successfully!</p>
            <p><strong>What's Next?</strong></p>
            <ul>
                <li>Your account is currently pending approval</li>
                <li>Please upload the required documents to activate your account</li>
                <li>Once approved, you'll be able to post jobs and manage applications</li>
            </ul>
            <p>You can access your dashboard using the button below:</p>
            <a href="{{ $dashboardUrl }}" class="btn">Go to Dashboard</a>
            <p>If you have any questions, please don't hesitate to contact our support team.</p>
            <p>Best regards,<br>The Phyzioline Team</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Phyzioline. All rights reserved.
        </div>
    </div>
</body>
</html>

