<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalHistory extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_histories';

    protected $fillable = [
        'partner_id',
        'amount',
        'status',
        'transfer_proof'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Diterima' => 'bg-green-100 text-green-800',
            'Diproses' => 'bg-yellow-100 text-yellow-800',
            'Ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'Diterima' => 'DITERIMA',
            'Diproses' => 'DIPROSES',
            'Ditolak' => 'DITOLAK',
            default => $this->status
        };
    }

    public function hasTransferProof()
    {
        return !is_null($this->transfer_proof);
    }

    public function getTransferProofUrlAttribute()
    {
        if ($this->transfer_proof) {
            return asset('storage/' . $this->transfer_proof);
        }
        return null;
    }
}