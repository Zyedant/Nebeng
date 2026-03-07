<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'id_fullname',
        'id_number',
        'id_image',
        'terminal_name',
        'terminal_province_id',
        'terminal_regency_id',
        'terminal_district_id',
        'terminal_map_coordinate',
        'terminal_address',
        'verified_status',
        'verified_status_message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function withdrawalHistories()
    {
        return $this->hasManyThrough(
            WithdrawalHistory::class,
            Partner::class,
            'user_id', 
            'partner_id', 
            'user_id', 
            'id' 
        );
    }

    public function getFormattedBalanceAttribute()
    {
        return 'Rp ' . number_format((float) $this->balance, 0, ',', '.');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'terminal_province_id');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'terminal_regency_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'terminal_district_id');
    }

    public function getFullLocationAttribute(): string
    {
        $location = [];
        
        if ($this->district) {
            $location[] = $this->district->name;
        }
        
        if ($this->regency) {
            $location[] = $this->regency->name;
        }
        
        if ($this->province) {
            $location[] = $this->province->name;
        }
        
        return implode(', ', $location);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->verified_status) {
            'Terverifikasi' => ['bg-green-100', 'text-green-800', 'Terverifikasi'],
            'Pengajuan' => ['bg-yellow-100', 'text-yellow-800', 'Pengajuan'],
            'Ditolak' => ['bg-red-100', 'text-red-800', 'Ditolak'],
            default => ['bg-gray-100', 'text-gray-800', 'Belum Verifikasi'],
        };
    }
}