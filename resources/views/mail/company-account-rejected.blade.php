<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Review</title>
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
            background-color: #f44336;
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
        .note-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
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
            <h1>Account Review Required</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Thank you for your interest in Phyzioline. After reviewing your company account, we need additional information or documentation to proceed.</p>
            @if($adminNote)
            <div class="note-box">
                <strong>Admin Note:</strong><br>
                {{ $adminNote }}
            </div>
            @endif
            <p><strong>What you can do:</strong></p>
            <ul>
                <li>Review the requirements and upload any missing documents</li>
                <li>Contact our support team if you have questions</li>
                <li>Resubmit your application once all requirements are met</li>
            </ul>
            <p>If you have any questions, please don't hesitate to contact our support team.</p>
            <p>Best regards,<br>The Phyzioline Team</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Phyzioline. All rights reserved.
        </div>
    </div>
</body>
</html>

