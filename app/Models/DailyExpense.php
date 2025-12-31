<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyExpense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'expense_number',
        'expense_date',
        'category',
        'description',
        'payment_method',
        'amount',
        'vendor_supplier',
        'created_by',
        'attachment'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Auto-generate expense number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $expense->expense_number = 'EXP-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Category translations
    public function getCategoryNameAttribute()
    {
        $categories = [
            'rent' => __('Rent'),
            'salaries' => __('Salaries'),
            'utilities' => __('Utilities'),
            'medical_supplies' => __('Medical Supplies'),
            'equipment_maintenance' => __('Equipment Maintenance'),
            'marketing' => __('Marketing'),
            'transportation' => __('Transportation'),
            'miscellaneous' => __('Miscellaneous')
        ];
        return $categories[$this->category] ?? $this->category;
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

