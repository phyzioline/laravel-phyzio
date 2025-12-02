<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
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
            text-align: center;
        }

        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 15px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            font-size: 22px;
            font-weight: bold;
        }

        .otp {
            font-size: 36px;
            font-weight: bold;
            color: #333333;
            background-color: #f0f0f0;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 8px;
            margin: 20px 0;
        }

        .message {
            font-size: 16px;
            color: #555555;
            margin-bottom: 20px;
        }

        .footer {
            font-size: 12px;
            color: #888888;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            OTP Verification
        </div>
        <p class="message">
            Hello,<br>
            Use the OTP below to complete your verification process:
        </p>
        <div class="otp">{{ $otp }}</div>
        <p class="message">
            This code will expire in 1 minute. Please do not share it with anyone.
        </p>
        <a href="#" class="btn">Verify Now</a>
        <div class="footer">
            If you didn't request this OTP, please ignore this email.<br>
            © {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>
</body>
</html>
