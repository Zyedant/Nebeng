<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerPostWithdrawalHistory extends Model
{
    use HasFactory;

    protected $table = 'partner_post_withdrawal_histories';

    protected $fillable = [
        'partner_posts_id',
        'amount',
        'status',
        'transfer_proof'
    ];

    public function partnerPost()
    {
        return $this->belongsTo(PartnerPost::class, 'partner_posts_id');
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
}