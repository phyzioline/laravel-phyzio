# 📧 Email Configuration for Phyzioline

## Gmail SMTP Setup

To configure email notifications from **phyzioline@gmail.com**, update your `.env` file with the following settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=phyzioline@gmail.com
MAIL_PASSWORD=your_gmail_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=phyzioline@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔑 How to Get Gmail App Password

1. **Login to Gmail Account:** phyzioline@gmail.com

2. **Go to Google Account Settings:**
   - Visit: https://myaccount.google.com/
   - Click on "Security" in the left sidebar

3. **Enable 2-Step Verification:**
   - Find "2-Step Verification"
   - Click "Get Started" and follow instructions
   - **This is required** for App Passwords

4. **Generate App Password:**
   - Go back to Security settings
   - Find "App passwords" (appears only after 2FA is enabled)
   - Click on "App passwords"
   - Select app: "Mail"
   - Select device: "Other (Custom name)"
   - Enter: "Phyzioline Laravel App"
   - Click "Generate"
   - **Copy the 16-character password**

5. **Update `.env` File:**
   - Paste the app password in `MAIL_PASSWORD`
   - **DO NOT use your regular Gmail password!**

---

## 📝 Steps to Update

### 1. **Edit `.env` File**

Location: `d:\laravel\phyzioline.com\.env`

**Update these lines:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=phyzioline@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx  # Replace with your 16-char app password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=phyzioline@gmail.com
MAIL_FROM_NAME="Phyzioline"
```

### 2. **Clear Config Cache**

Run these commands:
```bash
php artisan config:clear
php artisan cache:clear
```

### 3. **Test Email Configuration**

Create a test route (optional):
```php
Route::get('/test-email', function() {
    Mail::raw('Test email from Phyzioline!', function($message) {
        $message->to('your-test-email@example.com')
                ->subject('Test Email');
    });
    return 'Email sent!';
});
```

Visit: `http://your-domain.com/test-email`

---

## 🔧 Email Features to Implement

### 1. **Order Confirmation**

**File:** `app/Mail/OrderConfirmation.php`

```php
<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Order Confirmation - Phyzioline')
                    ->view('emails.order-confirmation');
    }
}
```

**Usage in Controller:**
```php
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

// After order is created
Mail::to($order->user->email)->send(new OrderConfirmation($order));
```

---

### 2. **Email OTP Verification**

**File:** `app/Mail/OTPVerification.php`

```php
<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your OTP Code - Phyzioline')
                    ->view('emails.otp-verification');
    }
}
```

**Usage:**
```php
// Generate OTP
$otp = rand(100000, 999999);

// Store in session or database
session(['otp' => $otp, 'otp_expires' => now()->addMinutes(5)]);

// Send email
Mail::to($user->email)->send(new OTPVerification($otp));
```

---

### 3. **Create Email Templates**

Create folder: `resources/views/emails/`

**File:** `resources/views/emails/order-confirmation.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px;">
        <h2 style="color: #02767F;">Order Confirmation</h2>
        <p>Dear {{ $order->user->name }},</p>
        <p>Thank you for your order! Your order #{{ $order->id }} has been confirmed.</p>
        
        <h3>Order Details:</h3>
        <p>Total: {{ $order->total }} EGP</p>
        
        <p>We'll send you another email when your order ships.</p>
        
        <p>Best regards,<br>Phyzioline Team</p>
    </div>
</body>
</html>
```

**File:** `resources/views/emails/otp-verification.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px;">
        <h2 style="color: #02767F;">OTP Verification</h2>
        <p>Your One-Time Password (OTP) is:</p>
        
        <h1 style="color: #02767F; font-size: 36px; letter-spacing: 5px; text-align: center;">
            {{ $otp }}
        </h1>
        
        <p>This code will expire in 5 minutes.</p>
        <p>If you didn't request this code, please ignore this email.</p>
        
        <p>Best regards,<br>Phyzioline Team</p>
    </div>
</body>
</html>
```

---

## ⚠️ Important Notes

1. **Security:** Never commit `.env` file to Git
2. **App Password:** Use Gmail App Password, not regular password
3. **2FA Required:** Gmail requires 2-Factor Authentication for App Passwords
4. **Rate Limits:** Gmail has sending limits (500 recipients/day for free accounts)
5. **Production:** Consider using services like SendGrid or Amazon SES for production

---

## 🐛 Troubleshooting

### Email not sending?

1. **Check `.env` configuration**
2. **Clear config cache:** `php artisan config:clear`
3. **Check Laravel logs:** `storage/logs/laravel.log`
4. **Verify app password** is correct
5. **Check Gmail account** isn't blocked

### Common Errors:

- **"Too many login attempts":** Wait 15-30 minutes
- **"Invalid credentials":** Use App Password, not regular password
- **"SMTP Error":** Check MAIL_PORT (587 for TLS, 465 for SSL)

---

**Created:** December 2, 2025  
**Last Updated:** December 2, 2025
