<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link',
        'impressions',
        'clicks',
        'ctr',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ctr' => 'decimal:2',
    ];

    /**
     * Calculate Click-Through Rate (CTR)
     */
    public function calculateCtr()
    {
        if ($this->impressions > 0) {
            $this->ctr = ($this->clicks / $this->impressions) * 100;
        } else {
            $this->ctr = 0;
        }
        $this->save();
        
        return $this->ctr;
    }
}
