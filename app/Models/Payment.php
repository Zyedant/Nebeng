<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_amount',
        'payment_proof',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeProcessed($query)
    {
        return $query->whereIn('status', ['Diterima', 'Ditolak']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Diproses');
    }

    public function isAccepted(): bool
    {
        return $this->status === 'Diterima';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Ditolak';
    }

    public function isPending(): bool
    {
        return $this->status === 'Diproses';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->payment_amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Diterima' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">DITERIMA</span>',
            'Diproses' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">DIPROSES</span>',
            'Ditolak' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">DITOLAK</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">' . strtoupper($this->status) . '</span>'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Diterima' => 'green',
            'Diproses' => 'yellow',
            'Ditolak' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'Diterima' => 'DITERIMA',
            'Diproses' => 'DIPROSES',
            'Ditolak' => 'DITOLAK',
            default => strtoupper($this->status)
        };
    }

    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            'credit_card' => 'Kartu Kredit',
            'e_wallet' => 'E-Wallet',
            default => ucfirst($this->payment_method)
        };
    }

    public function hasProof(): bool
    {
        return !is_null($this->payment_proof) && !empty($this->payment_proof);
    }

    public function getProofUrlAttribute(): ?string
    {
        if ($this->hasProof()) {
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }
}