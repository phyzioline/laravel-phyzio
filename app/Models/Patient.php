<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'date_of_birth',
        'gender',
        'address',
        'medical_history'
    ];

    protected $casts = [
        'date_of_birth' => 'date'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments()
    {
        return $this->hasMany(ClinicAppointment::class);
    }

    public function insurance()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function invoices()
    {
        return $this->hasMany(PatientInvoice::class);
    }

    public function payments()
    {
        return $this->hasMany(PatientPayment::class);
    }

    // Calculate total invoiced amount
    public function getTotalInvoicedAttribute()
    {
        return $this->invoices()->sum('final_amount');
    }

    // Calculate total paid amount
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('payment_amount');
    }

    // Calculate remaining balance
    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->total_invoiced - $this->total_paid);
    }

    // Get outstanding invoices
    public function getOutstandingInvoicesAttribute()
    {
        return $this->invoices()
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->get();
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
