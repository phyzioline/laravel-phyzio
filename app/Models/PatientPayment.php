<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'invoice_id',
        'payment_number',
        'payment_date',
        'payment_amount',
        'payment_method',
        'received_by',
        'notes',
        'receipt_path'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_amount' => 'decimal:2'
    ];

    // Auto-generate payment number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });

        static::saved(function ($payment) {
            // Update invoice status when payment is saved
            if ($payment->invoice_id) {
                $payment->invoice->updateStatus();
            }
        });

        static::deleted(function ($payment) {
            // Update invoice status when payment is deleted
            if ($payment->invoice_id) {
                $payment->invoice->updateStatus();
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function invoice()
    {
        return $this->belongsTo(PatientInvoice::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Payment method translations
    public function getPaymentMethodNameAttribute()
    {
        $methods = [
            'cash' => __('Cash'),
            'bank_transfer' => __('Bank Transfer'),
            'pos_card' => __('POS / Card'),
            'mobile_wallet' => __('Mobile Wallet')
        ];
        return $methods[$this->payment_method] ?? $this->payment_method;
    }
}

