<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partnervihecle extends Model
{
    use HasFactory;

    protected $table = 'partner_vihecles';

    protected $fillable = [
        'partner_id',
        'vihecle_type',
        'vihecle_plate_number',
        'vihecle_brand',
        'vihecle_name',
        'vihecle_color',
        'registration_number',
        'registration_vihecle_identity_number',
        'registration_engine_number',
        'registration_image',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function user()
    {
        return $this->partner->user();
    }

    public function getFormattedTypeAttribute()
    {
        return ucfirst(strtolower($this->vihecle_type));
    }
    
    public function getFullNameAttribute()
    {
        return "{$this->vihecle_brand} {$this->vihecle_name}";
    }
}