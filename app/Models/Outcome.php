<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_id',
        'date',
        'metric_name',
        'value_numeric',
        'value_text',
        'unit',
        'context'
    ];

    protected $casts = [
        'date' => 'date',
        'value_numeric' => 'float'
    ];

    public function episode()
    {
        return $this->belongsTo(EpisodeOfCare::class, 'episode_id');
    }
}
