<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'id_fullname',
        'id_number',
        'id_birth_date',
        'id_image',
        'dl_fullname',
        'dl_number',
        'dl_birth_date',
        'dl_image',
        'verified_status',
        'verified_status_message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vihecles(): HasMany
    {
        return $this->hasMany(Partnervihecle::class, 'partner_id');
    }
    public function vehicles(): HasMany
{
    // alias biar relasi standar "vehicles" bisa dipakai
    return $this->vihecles();
}

    /**
     * Get withdrawal histories for the partner.
     */
    public function withdrawalHistories(): HasMany
    {
        return $this->hasMany(WithdrawalHistory::class, 'partner_id');
    }

    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format((float) $this->balance, 0, ',', '.');
    }

    public function scopeVerified($query)
    {
        return $query->where('verified_status', 'Terverifikasi');
    }

    public function scopePending($query)
    {
        return $query->where('verified_status', 'Pengajuan');
    }

    public function scopeRejected($query)
    {
        return $query->where('verified_status', 'Ditolak');
    }

    public function scopeUnverified($query)
    {
        return $query->where('verified_status', 'Belum Verifikasi');
    }
}