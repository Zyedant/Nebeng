<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['regency_id', 'name'];

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function partnerPosts(): HasMany
    {
        return $this->hasMany(PartnerPost::class, 'terminal_district_id');
    }
}