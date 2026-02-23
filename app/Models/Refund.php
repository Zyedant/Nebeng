<?php
// app/Models/Refund.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reason',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'Diproses' => 'Diproses',
            'Diterima' => 'Diterima',
            'Ditolak' => 'Ditolak',
            default => 'Unknown'
        };
    }

    public function isAccepted()
    {
        return $this->status === 'Diterima';
    }

    public function isRejected()
    {
        return $this->status === 'Ditolak';
    }

    public function isProcessing()
    {
        return $this->status === 'Diproses';
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