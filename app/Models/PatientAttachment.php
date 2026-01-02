<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'title',
        'description',
        'category',
        'document_date',
        'appointment_id',
        'invoice_id'
    ];

    protected $casts = [
        'document_date' => 'date',
        'file_size' => 'integer'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function appointment()
    {
        return $this->belongsTo(ClinicAppointment::class);
    }

    public function invoice()
    {
        return $this->belongsTo(PatientInvoice::class);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get file icon based on type
     */
    public function getFileIconAttribute()
    {
        $type = strtolower($this->file_type ?? '');
        
        if (in_array($type, ['pdf'])) {
            return 'las la-file-pdf';
        } elseif (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'xray'])) {
            return 'las la-file-image';
        } elseif (in_array($type, ['doc', 'docx'])) {
            return 'las la-file-word';
        } else {
            return 'las la-file';
        }
    }
}

